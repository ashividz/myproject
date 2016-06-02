<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Cart;
use Session;

class CartCommentController extends Controller
{
    public function show($id)
    {
        $cart = Cart::with('comments')->find($id);

        return view('cart.modals.comment')->with('cart', $cart);
    }

    public function store(Request $request, $id)
    {
        Cart::find($id)->comments()->create($request->all());

        Session::flash('message', 'Comment added');
        Session::flash('status', 'Success');

        return back();
    }
}
