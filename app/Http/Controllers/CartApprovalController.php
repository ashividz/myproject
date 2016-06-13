<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Cart;
use App\Models\CartStatus;
use App\Models\Discount;
use App\Models\CartStep;
use App\Models\Patient;
use App\Models\Order;
use App\Models\OrderPatient;
use App\Models\ApproverPayment;
use App\Models\ApproverDiscount;
use App\Models\User;
use App\Support\Helper;

use Redirect;
use Auth;

class CartApprovalController extends Controller
{
    protected $daterange;
    protected $start_date;
    protected $end_date;

    public function __construct(Request $request)
    {
    
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y-m-d 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y-m-d 23:59:59');
        
    } 

    public function index()
    {
        return view('cart.approval');
    }

    public function show()
    {
        $roles = Helper::roles(); //dd($roles);

        $users = [];

        if (Auth::user()->hasRole('service_tl') || 
            Auth::user()->hasRole('service')) {
            
            $users = User::getUsersByRole('nutritionist');
            $users = $users->pluck('id');

        } elseif (Auth::user()->hasRole('sales_tl') || 
            Auth::user()->hasRole('sales')) {
            
            $users = User::getUsersByRole('cre');
            $users = $users->pluck('id');
        }; 

        //dd($users);

        $query = Cart::with('payments.method', 'steps', 'cre.employee.sup', 'step')
                    ->whereHas('approvers', function($q) use ($roles) {
                        $q->whereIn('approver_role_id', $roles);
                    })
                    ->whereBetween('updated_at', array($this->start_date, $this->end_date));

        if($users) {
            $query = $query->whereIn('created_by', $users);
        }

        $carts = $query->orderBy('id', 'desc')->get(); //dd($carts);

        $statuses = CartStatus::get();

        $data = array(
            'menu'          =>  'cart',
            'section'       =>  'approval',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'carts'         =>  $carts,
            'statuses'      =>  $statuses,
            'i'             =>  1
        );    

        return view('home')->with($data);
    }

    public function store(Request $request)
    {
        if ($request->get('state')) {
            foreach($request->get('state') as $id => $state_id)
            {
                $cart = Cart::find($id);
                $remark = $request->input('remark.'.$id);


                if ($state_id == 2) { //Rejected

                    CartStep::store($cart->id, $cart->status_id, $state_id, $remark);

                    Cart::updateState($cart->id, $state_id);

                } elseif ($state_id == 3) { //Approved

                    $count = CartStatus::count();

                    //Complete State for same step
                    CartStep::store($cart->id, $cart->status_id, $state_id, $remark);

                    if($cart->status_id < $count) {
                        //Create next CartStep
                        CartStep::nextStatus($id);
                        

                    }  else {                        
                        Cart::updateState($cart->id, $state_id); 
                    }    

                }  elseif ($state_id == 4) { //Multiple Discount Approvers
                    
                    //dd($request);

                    $step = CartStep::where('cart_id', $cart->id)->orderBy('id', 'desc')->first();
                    //$request->input('discount_id.'.$id); 
                    $discount_id = $step->discount_id + 1;
                    
                    //dd($discount_id);
                    $maxDiscount = max(array_pluck($cart->products, 'pivot.discount')); 
                    //dd($maxDiscount);

                    $discount = Discount::where('value', '<', $maxDiscount)
                                ->where('id', $discount_id)->first(); 
                                //dd($discount);

                    $state_id = $discount ? 4 : 3;

                    //Complete State for same step
                    CartStep::store($cart->id, $cart->status_id, $state_id, $remark, $discount_id); 

                    if(!$discount) {

                        //Create next CartStep
                        CartStep::nextStatus($id); 

                        Cart::updateState($cart->id, 1);        
                    }                 
                } 
            }

            $data = array(
                'message' => 'Process updated', 
                'status' => 'success'
            ); 

        } else if ($request->get('id')){

            $cart = Cart::find($request->get('id'));

            //Patient Registration
            $patient = Patient::register($cart);

            //if ($patient) {
            Order::store($cart, $patient);
            //}

            //Create Order
            //Order::store($cart);

            //Complete State for same step
            CartStep::store($cart->id, $cart->status_id, 3);

            $data = array(
                'message'       => 'Order Placed', 
                'status'        => 'success'
            );
        }

           

        return redirect('/cart/approval')->with($data);

    }

    public function modal($id)
    {
        $cart  = Cart::find($id);

        $data = array(
             
            'cart'     => $cart
        );    

        return view('cart.modals.update')->with($data);
    }

    public function update(Request $request, $id)
    {
        $cart  = Cart::find($id);

        //Reset Cart Step
        CartStep::store($cart->id, $cart->status_id, 1, $request->remark); 
        Cart::updateState($cart->id, 1);

        $data = array(
            'message' => 'Process updated', 
            'status' => 'success'
        );   

        return back()->with($data);
    }

    public function canApprovePayment(Request $request)
    {
        $cart = Cart::find($request->cart_id);
        if (!$cart || $cart->products->isEmpty() || $cart->payments->isEmpty()) {
            return 'false';
        }
        $methods = $cart->payments->pluck('payment_method_id');
        $approver = ApproverPayment::whereIn('payment_method_id', $methods)
                        ->where('approver_role_id', Auth::id())
                        ->first();

        if($approver) {
            return 'true';
        }

        return ['message' => 'Cannot approve payment method', 'status' => 'Error!'];;
    }

    public function canApproveDiscount(Request $request)
    {
        $cart = Cart::find($request->cart_id);
        if (!$cart) {
            return ['message' => 'Cart Not Found', 'status' => 'Error!'];
        }
        else if ($cart->products->isEmpty()) {
            return ['message' => 'No products in Cart', 'status' => 'Error!'];
        }
        else if ($cart->payments->isEmpty()) {
            return ['message' => 'No payment details in Cart', 'status' => 'Error!'];
        }

        $discount = $this->isDiscountSteps($cart, 1);

        if (!$discount) {
            return 'true';
        }

        //dd($discount);

        $approver = ApproverDiscount::where('discount_id', $discount->id)
                        ->where('approver_role_id', Auth::id())
                        ->first();

        if($approver) {
            return ['discount' => $discount->value, 'discount_id' => $discount->id ];
        }

        return ['message' => 'Cannot approve discount', 'status' => 'Error!'];
    }

    public function approve(Request $request)
    {
        $cart = Cart::find($request->cart_id);

        if (!$cart) {
            return ['message' => 'Cart Not Found', 'status' => 'Error!'];
        }

        $discount_id = $request->discount_id ? : null;

        if ($request->state == 1) {

            $discount = $this->isDiscountSteps($cart);

            if ($discount) { //Set State = Progress
                CartStep::store($request->cart_id, $cart->status_id, 4, $request->remark, $discount_id);
                Cart::updateState($cart->id, 4); 

                $discount = $this->isDiscountSteps($cart);
            } 

            

            if (!$discount) {
                CartStep::store($request->cart_id, $cart->status_id, 3, $request->remark, $discount_id);
                Cart::updateState($cart->id, 3); 

                if ($cart->status_id == 4) {
                    //Patient Registration
                    $patient = Patient::register($cart);

                    //Place order
                    Order::store($cart, $patient);

                } else { //If not Last Status, Set Next Step
                    CartStep::nextStatus($cart->id);
                }
            }

            return ['message' => 'Cart Approved', 'status' => 'Success!'];
            
        } 

        CartStep::store($request->cart_id, $cart->status_id, 2, $request->remark);
        Cart::updateState($cart->id, 2);         

        return ['message' => 'Cart Rejected', 'status' => 'Success!'];
    }

    private function isDiscountSteps($cart, $add = 1)
    {
        if ($cart->status_id <> 2 || $cart->products->isEmpty()) {
            return false;
        }

        $maxDiscount = !$cart->products->isEmpty() ? max(array_pluck($cart->products, 'pivot.discount')) : 0;

        if ($maxDiscount == 0) {
            return false;
        }

        $step = CartStep::where('cart_id', $cart->id)->orderBy('discount_id', 'desc')->first();

        $discount_id = $step->discount_id + $add;

        $discount = Discount::where('value', '<=', $maxDiscount + 5)
                                ->where('id', $discount_id)->first(); 
        //dd($discount);

        if ($discount) {
            return $discount;
        }

        return false;
    }
}