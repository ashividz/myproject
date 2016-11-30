<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\PaymentMethod;
use App\Models\Discount;
use App\Models\Role;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::all();

        //$approvers = array_pluck($method->approvers, 'id');

        $roles = Role::get();

        $data = array(
            'menu'          => 'settings',
            'section'       => 'cart.discount',
            'discounts'     =>  $discounts,
            'roles'         =>  $roles,
            'i'             => 1
        );

        return view('home')->with($data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'value' => 'required|unique:discounts'
        ]);

        $discount = new Discount;
        $discount->create($request->all());

        $data = array(
            'message' => 'Discount added', 
            'status' => 'success'
        );

        return back()->with($data);
    }

    public function update(Request $request)
    {
       if (trim($request->value) == '') {
            return "Error: Cannot be Null";
        }

        $discount = Discount::where('value', $request->value)
                        ->where('id', '<>', $request->id)
                        ->first();
        if($discount) {
            return "Error: Duplicate Value";
        }

        //return $request;

        $discount = Discount::find($request->id);
        $discount->update($request->all());

        return $request->value;
    }
}