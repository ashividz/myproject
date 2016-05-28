<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCartPaymentRequest;

use App\Models\Cart;
use App\Models\CartPayment;
use App\Models\PaymentMethod;
use Redirect;
use Carbon;

class CartPaymentController extends Controller
{
    public function show($id)
    {
        $cart = Cart::find($id);

        $methods = PaymentMethod::get();

        $data = array(
            'cart'         =>  $cart,
            'methods'       =>  $methods
        );

        return view('cart.modals.payment')->with($data);
    }

    public function store(CreateCartPaymentRequest $request, $id)
    {
        if(CartPayment::store($request, $id)) {

            $data = array(
                'message'   => 'Payment added',
                'status'    =>  'success'
            );

        } else {
            $data = array(
                'message'   => 'Error',
                'status'    =>  'fail'
            );
        }       

        return redirect('/cart/'.$id)->with($data);
    }

    public function destroy(Request $request, $id)
    {
        $payment = CartPayment::find($request->id);

        if ($payment) {                  
            
            CartPayment::destroy($request->id);

            //Update Order Payment
            Cart::updatePayment($id);

            $data = array(
                'message' => 'Successfully deleted', 
                'status' => 'success'
            );

        } else {
            $data = array(
                'message' => 'Error', 
                'status' => 'error'
            );
        }       
            
        return Redirect::to('/cart/'.$id)->with($data);
    }
}