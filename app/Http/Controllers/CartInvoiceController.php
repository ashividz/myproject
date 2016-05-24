<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Shipping;
use App\Models\CartInvoice;

use Auth;
use Session;

class CartInvoiceController extends Controller
{   

    public function modal($id)
    {
        $shipping = Shipping::where('cart_id', $id)->first();

        $data = [
            'shipping'      => $shipping
        ];
        return view('shipping.modal.invoice')->with($data);
    }

    public function store($id, Request $request)
    {
        if ($request->hasFile('invoice')) {
            $file = $request->file('invoice');

            $invoice = new CartInvoice;
            $invoice->cart_id = $id;
            $invoice->created_by = Auth::id();
            
            $invoice->file = base64_encode(file_get_contents($file->getRealPath()));;
            $invoice->mime = $file->getMimeType(); //application/pdf
            $invoice->size = $file->getSize(); //20,00,000 B
            
            $invoice->save();

            Session::flash('message', 'Invoice saved');
            Session::flash('status', 'success');
        }
        return back();
    }
}
