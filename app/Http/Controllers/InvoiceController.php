<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;

use App\Models\Cart;
use App\Models\Shipping;
use App\Models\Invoice;

use Auth;
use Session;

class InvoiceController extends Controller
{   

    public function show($id)
    {
        $invoice = Invoice::find($id);

        $data = [
            'invoice'      => $invoice
        ];
        return view('cart.modals.invoice')->with($data);
    }

    public function update(Request $request, $id)
    {        
        $invoice = Invoice::find($id);
        $invoice->number = $request->number;
        $invoice->amount = $request->amount;
        if ($request->hasFile('invoice')) {
            $file = $request->file('invoice'); 
            $invoice->file = base64_encode(file_get_contents($file->getRealPath()));;
            $invoice->mime = $file->getMimeType(); //application/pdf
            $invoice->size = $file->getSize(); //20,00,000 B
        }
        $invoice->save();

        Session::flash('message', 'Invoice Updated');
        Session::flash('status', 'success');

        return back();
    }

    public function modal($id)
    {
        $cart = Cart::find($id);

        $data = [
            'cart'      => $cart,
            'invoice'   => null
        ];
        return view('cart.modals.invoice')->with($data);
    }

    public function store($id, Request $request)
    {//dd($request);
        if ($request->hasFile('invoice')) {
            try {
                $file = $request->file('invoice');

                $invoice = new Invoice;
                $invoice->cart_id = $id;
                $invoice->number = $request->number;
                $invoice->amount = $request->amount;
                $invoice->created_by = Auth::id();
                
                $invoice->file = base64_encode(file_get_contents($file->getRealPath()));;
                $invoice->mime = $file->getMimeType(); //application/pdf
                $invoice->size = $file->getSize(); //20,00,000 B
                
                $invoice->save();

                return Cart::find($id)->invoices;

            } catch (QueryException $e) {
                if ($request->ajax() || $request->wantsJson()) {

                    return response([
                        'status'        => false,
                        'message'       => $e->getMessage()
                    ], 500);
                }
            }
            
        } else {
            return response([
                'status'        => false,
                'message'       => 'No file'
            ], 404);
        }
    } 
}
