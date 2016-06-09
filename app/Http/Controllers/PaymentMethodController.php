<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $methods = PaymentMethod::with('approvers')
                    ->orderBy('name')
                    ->get();

        $data = array(
            'menu'          => 'settings',
            'section'       => 'cart.payment_method',
            'methods'       =>  $methods,
            'i'             => 1
        );

        return view('home')->with($data);
    }

    public function get()
    {
        return PaymentMethod::get();
    }

    public function update(Request $request)
    {
       if (trim($request->value) == '') {
            return "Error: Cannot be Null";
        }

        $method = PaymentMethod::where('name', $request->value)
                        ->where('id', '<>', $request->id)
                        ->first();
        if($method) {
            return "Error: Duplicate Name";
        }

        //return $request;

        $method = PaymentMethod::find($request->id);
        $method->name =  $request->value;
        $method->save();

        return $request->value;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:payment_methods'
        ]);

        $method = new PaymentMethod;
        $method->name =  $request->name;
        $method->save();

        $data = array(
            'message' => 'Payment Method added', 
            'status' => 'success'
        );

        return back()->with($data);
    }
}
