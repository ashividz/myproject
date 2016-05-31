<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCartPaymentRequest;

use App\Models\Cart;
use App\Models\CartPayment;
use App\Models\PaymentMethod;
use Redirect;
use Excel;
use Carbon;

class CartPaymentController extends Controller
{
    protected $start_date;
    protected $end_date;

    public function __construct(Request $request)
    {
        $this->start_date = $request->start_date ? $request->start_date : Carbon::now()->format('Y-m-d'); 
        $this->end_date = $request->end_date ? $request->end_date : Carbon::now();
    }

    public function show($id)
    {
        $cart = Cart::find($id);

        $methods = PaymentMethod::get();

        $data = array(
            'cart'         =>  $cart,
            'methods'       =>  $methods
        );

        return view('cart.modals.payment')->with($data);
    }

    public function store(CreateCartPaymentRequest $request, $id)
    {
        if(CartPayment::store($request, $id)) {

            $data = array(
                'message'   => 'Payment added',
                'status'    =>  'success'
            );

        } else {
            $data = array(
                'message'   => 'Error',
                'status'    =>  'fail'
            );
        }       

        return redirect('/cart/'.$id)->with($data);
    }

    public function destroy(Request $request, $id)
    {
        $payment = CartPayment::find($request->id);

        if ($payment) {                  
            
            CartPayment::destroy($request->id);

            //Update Order Payment
            Cart::updatePayment($id);

            $data = array(
                'message' => 'Successfully deleted', 
                'status' => 'success'
            );

        } else {
            $data = array(
                'message' => 'Error', 
                'status' => 'error'
            );
        }       
            
        return Redirect::to('/cart/'.$id)->with($data);
    }

    public function get()
    {
        $payments = CartPayment::with('cart.status', 'cart.state', 'cart.products', 'method')
                    ->with(['cart.source' => function($q) {
                        $q->select('id', 'source_name as name');
                    }])
                    ->with(['cart.lead.patient' => function($q) {
                        $q->select('id', 'lead_id');
                    }])
                    ->with(['cart.invoices' => function($q) {
                        $q->select('id', 'cart_id', 'number');
                    }])
                    ->with('cart.creator.employee')
                    ->with('cart.cre.employee.supervisor.employee')
                    ->whereBetween('date', [$this->start_date, $this->end_date])
                    ->orderBy('id', 'desc')
                    ->get();

        return $payments;
    }

    
    public function download()
    {
        $payments = $this->get();

        if (!$payments->isEmpty()) {
            Excel::create('SalesPerformanceReport', function($excel) use($payments) {

                $excel->sheet('Payments', function($sheet) use($payments) {
                    //$sheet->fromArray($carts);
                        

                    $sheet->appendRow(array(
                           'Date', 
                           'Cart Status',
                           'Cart Id', 
                           'Lead Id',
                           'Lead Source',
                           'CRE',
                           'TL',
                           'Name',
                           'Patient Id',
                           'Payment Date',
                           'Payment Method',
                           'Payment Amount',
                           'Payment Remark',
                        ));
                    foreach ($payments as $payment) {
                        $date       = $payment->created_at;
                        $status     = $payment->cart->status->name." - ".$payment->cart->state->name;
                        $id         = $payment->cart_id;
                        $lead_id    = $payment->cart->lead_id;
                        $source     = $payment->cart->source['name'];
                        $cre        = isset($payment->cart->cre->employee) ? $payment->cart->cre->employee->name : '';
                        $tl         = isset($payment->cart->cre->employee->supervisor) ? $payment->cart->cre->employee->supervisor->employee->name: '' ;
                        $name       = $payment->cart->lead->name;
                        $patient_id = isset($payment->cart->lead->patient) ? $payment->cart->lead->patient->id : '' ;
                        $payment_date   = $payment->date;
                        $payment_method = $payment->method->name;
                        $payment_amount = $payment->amount;
                        $payment_remark = $payment->remark;
                        

                        $sheet->appendRow(array(
                            $date,
                            $status,
                            $id,
                            $lead_id,
                            $source,
                            $cre,
                            $tl,
                            $name,
                            $patient_id,
                            $payment_date,
                            $payment_method,
                            $payment_amount,
                            $payment_remark
                        ));
                    }
                });
            })->download('xls');;
        }
    }
}