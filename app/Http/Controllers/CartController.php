<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\CreateCartRequest;

use App\Models\Lead;
use App\Models\Cart;
use App\Models\CartStep;
use App\Models\CartProduct;
use App\Models\LeadProgram;
use App\Models\OrderProduct;
use App\Models\CartStatus;
use App\Models\Currency;
use App\Models\ProductCategory;
use App\Models\Carrier;
use App\Models\User;
use App\Models\Region;
use App\Models\Country;
use App\Models\Cod;

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
        $lead    = Lead::with('addresses')
                ->find($id);
        
        $regions = Region::whereIn('region_code',array_pluck($lead->addresses,'state'))->get();

        $countries = Country::all();        
        
        foreach ($lead->addresses as $key => $value) {
            $lead->addresses[$key]->cod = Cod::checkAvailability($value->zip);
        }


        $statuses = CartStatus::get();

        if (!$lead) {
            return "Lead Not found";
        }

        $currencies = Currency::get();

        $categories = ProductCategory::get();

        if (Auth::user()->hasRole('sales_tl') || Auth::user()->hasRole('sales')) {
            $users = User::getUsersByRole('cre');
        } elseif (Auth::user()->hasRole('service_tl') || Auth::user()->hasRole('service')) {
            $users = User::getUsersByRole('nutritionist');
        } else {
            $users = User::select('users.id', 'e.name')
                        ->join('employees as e', 'e.id', '=', 'users.emp_id')
                        ->orderBy('e.name')
                        ->get();
        }
        

        $data = array(
            'menu'          => 'lead',
            'section'       => 'partials.cart',
            'lead'          =>  $lead,
            'statuses'      =>  $statuses,
            'currencies'    =>  $currencies,
            'categories'    =>  $categories,
            'users'         =>  $users,
            'regions'       =>  $regions,
            'countries'     =>  $countries,
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

    public function store(Request $request, $id)
    {
        $this->validate($request, [
            'currency_id'           => 'required',
            //'shipping_address_id'   => 'required',
            'cre_id'                =>  'required',
            'source_id'             =>  'required',
        ]);

        $lead = Lead::find($id);

        $cart = $lead->carts()->create($request->all());

        CartStep::store($cart->id, 1, 1);

        return $cart;


        /*

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
                $cart->cre_id = $request->cre;
                $cart->source_id = $lead->source_id;//$request->source;
                $cart->currency_id = $request->currency;
                if($request->shipping_address_id !=''){
                    $cart->shipping_address_id = $request->shipping_address_id;
                }
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
    */        
    }

    public function process($id)
    {
        //Update OrderStep
        CartStep::store($id, 1, 3);

        //Create next OrderStep
        CartStep::nextStatus($id);

        /*$data = array(
            'message' => 'Order Processed', 
            'status' => 'success'
        );  
        
        return redirect('/cart/'.$id)->with($data);*/
    }

    public function show($id)
    {
        //Update the Order Amount
        //Cart::updateAmount($id);
        


        $cart = Cart::with('currency', 'products.category','status', 'state', 'steps', 'shippingAddress','shippings.trackingStatus')
                ->with(['creator' => function($q) {
                    $q->withTrashed();
                }])
                ->find($id);

        if (!$cart) {
            abort('400', 'Cart not found');
        }
        $cart = $cart->updateAmount();

        foreach ($cart->shippings as $shipping) {
            $shipping = $this->statusClass($shipping);
        }
        //dd(Cart::setDietDuration($cart));

        //return $cart->getDietDiscount();

        //return Cart::setDietDuration($cart)->duration;

         
        /*dd($cart->products()
            ->whereIn('product_category_id', [3,4,5])
            ->sum('amount')
            //->first()
            );
            dd($cart->payments()
            ->sum('amount')
            //->first()
            );*/
                
        //dd($cart->products->sum('pivot.quantity'));
                    /*->join('cart_product as cp', 'cp.product_id', '=', 'products.id')
                    ->where('product_category_id', 1)
                    ->get());*/
                    
                    //->sum(DB::RAW('cp.quantity')));
            //->get());
        
        $regions   = '';
        $countries = '';
        if($cart->shippingAddress){
            $cart->shippingAddress->cod = Cod::checkAvailability($cart->shippingAddress->zip);
            $regions   = Region::where('region_code',$cart->shippingAddress->state)->get();
            $countries = Country::where('country_code',$cart->shippingAddress->country)->get();
        }
        $address = $cart->shippingAddress;

        $statuses = CartStatus::get();

        $data = array(
            'cart'      =>  $cart, 
            'address'   =>  $address,
            'statuses'  =>  $statuses,
            'regions'   =>  $regions,
            'countries' =>  $countries,
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
        $categories = $request->categories ? : null;

        $carts = Cart::with('currency', 'status', 'state', 'products', 'payments.method', 'shippings.carrier', 'comments.creator.employee', 'proforma', 'invoices', 'shippingAddress')
                    ->with(['source' => function($q) {
                        $q->select('id', 'source_name as name');
                    }])
                    ->with(['lead.patient' => function($q) {
                        $q->select('id', 'lead_id');
                    }])
                    ->with('shippings.carrier')
                    ->with('steps.status', 'steps.state', 'steps.creator.employee')
                    ->with('creator.employee')
                    ->with('cre.employee.supervisor.employee');

        if (Auth::user()->hasRole('cre') || (Auth::user()->hasRole('nutritionist') && !Auth::user()->hasRole('service_tl'))) {
            $carts = $carts->where('cre_id', Auth::id());
        }

        if ($categories) {
            $carts = $carts->whereHas('products.category', function($q) use($categories) {
                        $q->whereIn('id', $categories);
                    }); 
        }

        if ($request->statuses) {
            $carts = $carts->whereIn('status_id', $request->statuses); 
        }

        switch($request->role) {
            case 'nutritionist':
                $roles = User::getUsersByRole('nutritionist');
                break;

            case 'cre':
                $roles = User::getUsersByRole('cre');
                break; 

            default:
                $roles = null;
                break;
        }

        if ($roles) {
            $carts = $carts->whereIn("cre_id", $roles->pluck('id'));
        }

        switch($request->filter) {
            case 'pi':
                $carts = $carts->whereHas('payments', function($q){
                    $q->whereIn('payment_method_id', [2, 3]);
                 });
                break;

            case 'fedex':
                $carts = $carts->whereHas('payments', function($q){
                    $q->where('payment_method_id', 4)
                        ->where('status_id', '>=', 2);
                 });
                break; 

            case 'cod':
                $carts = $carts->where('status_id', '=', 3)
                            ->whereHas('payments', function($q){
                                $q->whereIn('payment_method_id', [2, 4]);
                             });
                break; 

            case 'payment':
                $carts = $carts->where('status_id', '=', 3)
                            ->whereHas('payments', function($q){
                                $q->whereNotIn('payment_method_id', [2, 4]);
                             });
                break; 

            case 'order':
                $carts = $carts->where('status_id', '=', 4)->where('state_id', '=', 1);
                break; 

            case 'shipping':
                $carts = $carts->has('invoices', '>', 0);
                break; 
        }

        if(isset($request->referenceFilter) && $request->referenceFilter=='reference')
        {
            $carts = $carts->has('benefitCart', '>', 0);
        }

        $carts = $carts->whereBetween('updated_at', [$this->start_date, $this->end_date])
                    ->orderBy('id', 'desc')
                    ->get();

        return $carts;
    }

    public function find(Request $request)
    {
        $cart = Cart::with('currency', 'status', 'state', 'products.category', 'payments.method', 'shippings.carrier', 'comments.creator.employee', 'proforma', 'lead.programs', 'lead.region', 'address.region')
                    ->with(['source' => function($q) {
                        $q->select('id', 'source_name as name');
                    }])
                    ->with(['lead.patient' => function($q) {
                        $q->select('id', 'lead_id');
                    }])
                    ->with(['invoices' => function($q) {
                        $q->select('id', 'cart_id', 'number', 'amount');
                    }])

                    ->with(['shippings.carrier' => function($q) {
                        //$q->select('id', 'cart_id', 'number', 'amount');
                    }])
                    ->with('steps.status', 'steps.state', 'steps.creator.employee')
                    ->with('creator.employee')
                    ->with('cre.employee.supervisor.employee');

        if ($request->statuses) {
            $cart->whereIn('status_id', $request->statuses);
        }
                    
        return $cart->find($request->cart_id);
    }

    public function goods()
    {
        $data = array(
            'menu'          =>  'cart',
            'section'       =>  'reports.goods',
        );    

        return view('home')->with($data);
    }

    public function canCreateCart(Request $request)
    {
        $lead = Lead::find($request->id);

        if (!$lead) {
            return ['status' => 'false', 'message'  => 'Lead not available'];
        }

        /*$cart = $lead->hasIncompleteDietCart();
        if ($cart) {
            return [
                'status'    =>  'false', 
                'message'   =>  'Incomplete Diet cart exists', 
                'cart'      =>  $cart
            ];
        }*/

        if (!Auth::user()->canCreateCartForOthers()) {

            $cart = Cart::hasIncompleteCart($lead);
                            //dd($cart);
            if ($cart) {
                return [
                    'status'    =>  'false', 
                    'message'   =>  'Incomplete cart exists', 
                    'cart'      =>  $cart
                ];
            }
        }

            
        if (!Auth::user()->canCreateCartForOthers()) {
            $cart = $lead->carts()->where('balance', '>', 0)
                            ->first();

            if ($cart) {
                return [
                    'status'    =>  'false', 
                    'message'   =>  'Balance Payment remaining', 
                    'cart'      =>  $cart
                ];
            }
        }

         if($lead->country == 'IN' && (trim($lead->address) == '' || $lead->zip == 0 || trim($lead->zip) == '' )) {

            return ['status' => 'false', 'message'  => 'Lead details not complete'];
        }

        if($lead->dob <> '' && $lead->gender <> '' && $lead->email <> '' && $lead->phone <> '' && $lead->country <> '' && $lead->state <> '' && $lead->city <> '' && $lead->source_id <> '') {

            return ['status' => 'true'];

        } else {
            return ['status' => 'false', 'message'  => 'Lead details not complete'];
        }


    }


     public function canCreateReferenceCart(Request $request)
    {
        $lead = Lead::find($request->id);

        if (!$lead) {
            return ['status' => 'false', 'message'  => 'Lead not available'];
        }

        /*$cart = $lead->hasIncompleteDietCart();
        if ($cart) {
            return [
                'status'    =>  'false', 
                'message'   =>  'Incomplete Diet cart exists', 
                'cart'      =>  $cart
            ];
        }*/

      if($lead->country == 'IN' && (trim($lead->address) == '' || $lead->zip == 0 || trim($lead->zip) == '' )) {

            return ['status' => 'false', 'message'  => 'Lead details not complete'];
        }

        if($lead->dob <> '' && $lead->gender <> '' && $lead->email <> '' && $lead->phone <> '' && $lead->country <> '' && $lead->state <> '' && $lead->city <> '' && $lead->source_id <> '') {

            return ['status' => 'true'];

        } else {
            return ['status' => 'false', 'message'  => 'Lead details not complete'];
        }


    }


    public function activate($id)
    {
        $cart = Cart::find($id);

        if (!$cart) {
            return false;
        }
        if (!Auth::user()->canActivateCart($cart)) {
            return false;
        }

        CartStep::store($id, 1, 1, "Cart Activated for Extension or Balance Payment");

        return $cart;
    }

    public function search(Request $request)
    {
        return Cart::with('products', 'payments.method', 'currency', 'status', 'state', 'proforma', 'comments.creator.employee', 'invoices', 'shippingAddress')
                    ->with(['source' => function($q) {
                        $q->select('id', 'source_name as name');
                    }])
                    ->with(['lead.patient' => function($q) {
                        $q->select('id', 'lead_id');
                    }])
                    ->with('steps.status', 'steps.state', 'steps.creator.employee')
                    ->with('creator.employee')
                    ->with('cre.employee.supervisor.employee')
                    ->find($request->id);
    }

    private function statusClass($shipping)
    {
        if(!isset($shipping->status_detail->Code)) {
            return false;
        }
        switch ($shipping->status_detail->Code) {                
            case 'OC':
                $shipping->status_class = 'in_progress';
                break;
            case 'PU':
                $shipping->status_class = 'picked_up';
                break;
            case 'FD':
                $shipping->status_class = 'in_transit';
                break;
            case 'DE':
                $shipping->status_class = 'exception';
                break;
            case 'DL':
                $shipping->status_class = 'delivered';
                break;
            default:
                $shipping->status_class = 'in_transit';
        }

        return $shipping;
    }

}
