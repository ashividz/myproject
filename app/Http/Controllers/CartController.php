<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\CreateCartRequest;

use App\Models\Lead;
use App\Models\Cart;
use App\Models\CartStep;
use App\Models\LeadProgram;
use App\Models\OrderProduct;
use App\Models\CartStatus;
use App\Models\Currency;
use App\Models\ProductCategory;
use App\Models\Carrier;
use Auth;
use Redirect;
use DB;
use Excel;
use Carbon;

class CartController extends Controller
{
    protected $start_date;
    protected $end_date;

    public function __construct(Request $request)
    {
        $this->start_date = $request->start_date ? $request->start_date : Carbon::now()->format('Y-m-d'); 
        $this->end_date = $request->end_date ? $request->end_date : Carbon::now();

    }

    public function index($id)
    {
        $lead = Lead::find($id);

        $statuses = CartStatus::get();

        if (!$lead) {
            return "Lead Not found";
        }

        $currencies = Currency::get();

        $categories = ProductCategory::get();

        $data = array(
            'menu'          => 'lead',
            'section'       => 'partials.cart',
            'lead'          =>  $lead,
            'statuses'      =>  $statuses,
            'currencies'    =>  $currencies,
            'categories'    =>  $categories
        );

        return view('home')->with($data);
    }

    public function create($id)
    {
    	$lead = Lead::find($id);

        $currencies = Currency::get();

        $categories = ProductCategory::get();

    	if (!$lead) {
    		return "Lead Not found";
    	}

    	$data = array(
    		'lead' 		     =>	$lead,
            'currencies'     => $currencies,
            'categories'     => $categories
    	);

    	return view('cart.modal.create')->with($data);
    }

    public function store(CreateCartRequest $request)
    {
        //dd($request->programs);
        $lead = Lead::find($request->id);

        if ($lead) {

            return DB::transaction(function() use ($request, $lead) {

                $cart = Cart::where('lead_id', $lead->id)
                            ->orderBy('id', 'desc')
                            ->first();

                if(isset($cart)) {
                    if ($cart->status_id <> 4 || ($cart->status_id == 4 && $cart->state_id <> 3)) {
                        
                        $data = array(
                            'message' => 'Incomplete Cart already exists. Cannot create a new cart', 
                            'status' => 'error'
                        );

                        return back()->with($data);
                    }
                }

                $cart = new Cart;
                $cart->lead_id = $request->id;
                $cart->cre_id = $lead->cres->first()->user_id;//$request->cre;
                $cart->source_id = $lead->source_id;//$request->source;
                $cart->currency_id = $request->currency;
                $cart->created_by = Auth::id();

                $cart->save();

                //Save the Programs
                //OrderProgram::store($cart->id, $request->programs);

                //Update the Order Amount
                //Cart::updateAmount($cart->id);

                //Create new OrderStep
                CartStep::store($cart->id, 1, 1);

                $data = array(
                    'message' => 'Cart Created', 
                    'status' => 'success'
                );

                return Redirect::to('/cart/'.$cart->id)->with($data);

            });

        } else {

            $data = array(
                'message' => 'Lead not found', 
                'status' => 'error'
            );


            return Redirect::to('/lead/'.$lead->id.'/order')->with($data);
        }           
            
    }

    public function process($id)
    {
        //Update OrderStep
        CartStep::store($id, 1, 3);

        //Create next OrderStep
        CartStep::nextStatus($id);

        $data = array(
            'message' => 'Order Processed', 
            'status' => 'success'
        );  
        
        return redirect('/cart/'.$id)->with($data);
    }

    public function show($id)
    {
        //Update the Order Amount
        Cart::updateAmount($id);
        
        $cart = Cart::with('currency', 'products.category','status', 'state', 'steps')
            ->find($id); 

        $statuses = CartStatus::get();

        $data = array(
            'cart'     =>  $cart, 
            'statuses'  =>  $statuses
        );

        return view('cart.index')->with($data);
    }

    public function shipping($id)
    {
        $carriers = Carrier::all();

        $data   = [
            'id'        => $id,
            'carriers'  => $carriers
        ];

        return view('shipping.modal.add')->with($data);
    }

    public function getCarts()
    {
        $start_date = $this->start_date;
        $end_date = $this->end_date;

        $carts = Cart::with('currency', 'status', 'state', 'products', 'payments.method')
                    ->with(['source' => function($q) {
                        $q->select('id', 'source_name as name');
                    }])
                    ->with(['lead.patient' => function($q) {
                        $q->select('id', 'lead_id');
                    }])
                    ->with('cre.employee.supervisor.employee')
                    ->whereHas('payments', function($q) use ($start_date, $end_date) {
                        $q->whereBetween('created_at', [$start_date, $end_date]);
                    })
                    //->whereBetween('created_at', [$this->start_date, $this->end_date])
                    ->orderBy('id', 'desc')
                    ->get();

        return $carts;
    }
    
    public function download()
    {
        $carts = $this->getCarts();

        if (!$carts->isEmpty()) {
            Excel::create('SalesPerformanceReport', function($excel) use($carts) {

                $excel->sheet('Sheetname', function($sheet) use($carts) {
                    //$sheet->fromArray($carts);
                        

                    $sheet->appendRow(array(
                           'Date', 
                           'Cart Status',
                           'Cart Id', 
                           'Lead Id',
                           'Lead Source',
                           'CRE',
                           'TL',
                           'Name',
                           'Patient Id',
                           'Amount',
                           'Payment Date',
                           'Payment Method',
                           'Payment Amount',
                           'Payment Remark',
                        ));
                    foreach ($carts as $cart) {
                        $date       = $cart->created_at;
                        $status     = $cart->status->name." - ".$cart->state->name;
                        $id         = $cart->id;
                        $lead_id    = $cart->lead_id;
                        $source     = $cart->source['name'];
                        $cre        = $cart->cre->employee->name;
                        $tl         = isset($cart->cre->employee->supervisor) ? $cart->cre->employee->supervisor->employee->name: '' ;
                        $name       = $cart->lead->name;
                        $patient_id = isset($cart->lead->patient) ? $cart->lead->patient->id : '' ;
                        $amount     = $cart->amount; 
                        $payment_date   = !$cart->payments->isEmpty() ? $cart->payments->last()->date : null;
                        $payment_method = !$cart->payments->isEmpty() ? $cart->payments->last()->method->name : null;
                        $payment_amount = !$cart->payments->isEmpty() ? $cart->payments->last()->amount : null;
                        $payment_remark = !$cart->payments->isEmpty() ? $cart->payments->last()->remark : null;
                        

                        $sheet->appendRow(array(
                            $date,
                            $status,
                            $id,
                            $lead_id,
                            $source,
                            $cre,
                            $tl,
                            $name,
                            $patient_id,
                            $amount,
                            $payment_date,
                            $payment_method,
                            $payment_amount,
                            $payment_remark
                        ));
                    }
                });
            })->download('xls');;
        }
    }
}
