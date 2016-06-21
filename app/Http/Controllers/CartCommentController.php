<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Cart;
use App\Models\Notification;
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
        $cart = Cart::find($id);
        $cart->comments()->create($request->all());

        Notification::store(6, $id, $cart->cre_id);

        return $cart->comments()->with('creator.employee')->get();
    }
}
