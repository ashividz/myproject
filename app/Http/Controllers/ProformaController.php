<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Cart;
use App\Models\Proforma;
use Auth;
use PDF;

class ProformaController extends Controller
{
    public function show($id)
    {
        $cart = Cart::find($id);

        $data = [
            'cart'  =>  $cart
        ];

        return view('cart.partials.proforma')->with($data);
    }
    public function download($id)
    {
        $cart = Cart::with('currency', 'products', 'proforma')
                ->find($id);

        if(!$cart->proforma) {
            $cart->proforma()->save( new Proforma ([
                'status_id'     => 1,
                'created_by'    => Auth::id()
            ]));
        }

        $data = [
            'cart'  =>  $cart
        ];

        $pdf = PDF::loadView('cart.partials.proforma', $data);
        return $pdf->download('proforma_invoice.pdf');
    }
}
