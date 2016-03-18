<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Cart;
use App\Models\WorkflowStatus;
use App\Models\CartStep;
use App\Models\Patient;
use App\Models\Order;
use App\Models\OrderPatient;
use Redirect;

class CartApprovalController extends Controller
{
    public function show()
    {
        $carts = Cart::select('carts.*')
                    ->with('payments.method', 'steps')
                    ->join('cart_approver as ca', 'ca.status_id', '=', 'carts.status_id')
                    ->orderBy('carts.id', 'desc')
                    ->get(); //dd($carts);

        $statuses = WorkflowStatus::get();

        $data = array(
             
            'carts'     => $carts,
            'statuses'  => $statuses,
            'i'         =>  1
        );    

        return view('cart.approval')->with($data);
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

                    $count = Status::count();

                    //Complete State for same step
                    CartStep::store($cart->id, $cart->status_id, $state_id, $remark);

                    if($cart->status_id < $count) {
                        //Create next CartStep
                        CartStep::nextStatus($id);
                        

                    }  else {                        
                        Cart::updateState($cart->id, $state_id); 
                    }    

                }  elseif ($state_id == 4) { //Multiple Discount Approvers
                    

                    $discount_id = $request->input('discount_id.'.$id); //dd($discount_id);
                    //$discount_id = $discount_id == 1 ? 1 : $discount_id - 1;
                    

                    $discount = Discount::where('value', '<=', 25)
                                ->where('id', $discount_id)->first(); //dd($discount);

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

            if ($patient) {
                Order::store($cart);
            }

            //Create Order
            //Order::store($cart);

            //Complete State for same step
            CartStep::store($cart->id, $cart->status_id, 3);

            $data = array(
                'message' => 'Patient Registered', 
                'status' => 'success'
            );
        }

           

        return redirect('/cart/approval')->with($data);

    }
}