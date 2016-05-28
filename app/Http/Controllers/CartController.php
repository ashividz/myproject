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

    public function get(Request $request)
    {
        $category = $request->category ? : null;

        $carts = Cart::with('currency', 'status', 'state', 'products', 'payments.method', 'shippings.carrier')
                    ->with(['source' => function($q) {
                        $q->select('id', 'source_name as name');
                    }])
                    ->with(['lead.patient' => function($q) {
                        $q->select('id', 'lead_id');
                    }])
                    ->with(['invoices' => function($q) {
                        $q->select('id', 'cart_id', 'number');
                    }])
                    ->with('creator.employee')
                    ->with('cre.employee.supervisor.employee');

        if ($category) {
            $carts = $carts->whereHas('products.category', function($q){
                        $q->whereIn('id', [2,4]);
                    }); 
        }

        $carts = $carts->whereBetween('created_at', [$this->start_date, $this->end_date])
                    ->orderBy('id', 'desc')
                    ->get();

        return $carts;
    }

    public function goods()
    {
        $data = array(
            'menu'          =>  'cart',
            'section'       =>  'reports.goods',
        );    

        return view('home')->with($data);
    }
}
