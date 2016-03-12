<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Cart;
use App\Models\CartStep;

class RegistrationController extends Controller
{    

    public function show($id)
    {
        $cart = Cart::find($id);

        $data = array(
            'cart'     =>  $cart
        ); 
        return view('cart.modals.update')->with($data);
    }

    public function update(Request $request, $id)
    {
        CartStep::startState($id, $request->remark);

        $data = array(
            'message' => 'Registration Process Update', 
            'status' => 'success'
        );    

        return redirect('/cart/'.$id)->with($data);
    }
}