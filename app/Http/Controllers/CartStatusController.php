<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\CreateCartRequest;

use App\Models\Cart;
use App\Models\CartStatus;

class CartStatusController extends Controller
{
    public function index()
    {
        $statuses = CartStatus::get();

        $data = array(
            'menu'          => 'settings',
            'section'       => 'cart.status',
            'statuses'      =>  $statuses
        );

        return view('home')->with($data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:cart_statuses'
        ]);

        $status = new CartStatus;
        $status->create($request->all());

        $data = array(
            'message' => 'Payment Method added', 
            'status' => 'success'
        );

        return back()->with($data);
    }
}