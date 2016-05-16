<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Tracking;
use App\Models\TrackingInvoice;

use Auth;
use Session;

class TrackingInvoiceController extends Controller
{   

    public function modal($id)
    {
        $tracking = Tracking::find($id);

        $data = [
            'tracking'      => $tracking,
            'invoice'       => $tracking->invoice
        ];
        return view('shipping.modal.invoice')->with($data);
    }

    public function store($id, Request $request)
    {
        if ($request->hasFile('invoice')) {
            $file = $request->file('invoice');
            $invoice = TrackingInvoice::where('tracking_id', $id)->first();

            if (!$invoice) {
                $invoice = new TrackingInvoice;
            }
            $invoice->tracking_id = $id;
            $invoice->file = base64_encode(file_get_contents($file->getRealPath()));;
            $invoice->mime = $file->getMimeType(); //application/pdf
            $invoice->size = $file->getSize(); //20,00,000 B
            $invoice->created_by = Auth::id();
            $invoice->save();

            Session::flash('message', 'Invoice saved');
            Session::flash('status', 'success');
        }
        return back();
    }
}
