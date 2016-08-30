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

    public function getCartsForInvoices(Request $request)
    {
        $start_date = $request->start_date ? : Carbon::now()->subDays(30)->format('Y-m-d');
        $end_date = $request->end_date ? : Carbon::now()->format('Y-m-d');

        $query = Cart::with('products', 'payments.method', 'currency', 'status', 'state', 'proforma', 'comments.creator.employee', 'invoices', 'shippingAddress')
                    ->with(['source' => function($q) {
                        $q->select('id', 'source_name as name');
                    }])
                    ->with(['lead.patient' => function($q) {
                        $q->select('id', 'lead_id');
                    }])
                    ->with('steps.status', 'steps.state', 'steps.creator.employee')
                    ->with('creator.employee')
                    ->with('cre.employee.supervisor.employee');
                    
                    
                    //->where('state_id', 1)
                    //->whereBetween('created_at', [$start_date, $end_date])
                    /*->orWhere(function($q) use ($start_date, $end_date) {
                        $q->whereHas('payments', function($q) {
                            $q->whereIn('payment_method_id', [4,5]);
                        })
                                               
                    })*/
        if ($request->pending == 'true') {
            $query->has('invoices', '=', 0);
        }

        if ($request->cod == 'true') {
            $query->whereHas('payments', function($q){
                    $q->whereIn('payment_method_id', [2, 4])
                        ->where('status_id', '>', 2);
                });
        } else {
            $query->where('status_id', 4);
        }
                    
        $carts = $query->whereBetween('created_at', [$start_date, $end_date])
                    ->limit(100)
                    ->orderBy('id', 'desc')
                    ->get();

        return $carts;
    }
}
