<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Support\Helper;
use App\Models\Lead;
use App\Models\WebOrder;
use App\Models\WebOrderMeta;
use Auth;
use DB;
use Woocommerce;

class WebsiteController extends Controller
{
	public function onlinePayments(Request $request)
	{
		//header("Access-Control-Allow-Origin: *");

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

    	$orders = DB::connection('mysql2')
	    	->table('orders AS o')
	    	->join('paymentprocessors as pp', 'pp.pp_id', '=', 'o.pp_id')
	    	->whereBetween('order_date', array($start_date, $end_date))
	        ->orderBy('order_date', 'DESC')
	    	->get(array('phone','pp_transaction_id AS transaction_id', 'order_date', 'firstname', 'lastname', 'o.status AS payment_status' , 'country', 'city', 'pp_name AS payment_method', 'currency', 'total_amount', 'message'));
	    	//var_dump($orders);
    	foreach ($orders as $order) {
    		$lead = Lead::with('cre')
    				->where('phone', $order->phone)
    				->orWhere('mobile', $order->phone)
    				->first();

    		if ($lead) {
    			$order->lead_id = $lead->id;
    		}
    		if (isset($lead->cre)) {    			
    			$order->cre = $lead->cre->cre;
    		}
    		
    	}
    	return json_encode($orders);
	}

    public function onlinenewPayments(Request $request)
    {
        //header("Access-Control-Allow-Origin: *");

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $orders = DB::connection('mysql4')
            ->table('orders AS o')
            ->join('paymentprocessors as pp', 'pp.pp_id', '=', 'o.pp_id')
            ->whereBetween('order_date', array($start_date, $end_date))
            ->orderBy('order_date', 'DESC')
            ->get(array('phone','pp_transaction_id AS transaction_id', 'order_date', 'firstname', 'lastname', 'o.status AS payment_status' , 'country', 'city', 'pp_name AS payment_method', 'currency', 'total_amount', 'message'));
            //dd($orders);
        foreach ($orders as $order) {
            $lead = Lead::with('cre')
                    ->where('phone', $order->phone)
                    ->orWhere('mobile', $order->phone)
                    ->first();

            if ($lead) {
                $order->lead_id = $lead->id;
            }
            if (isset($lead->cre)) {                
                $order->cre = $lead->cre->cre;
            }
            
        }
        //dd($orders);
        return json_encode($orders);
    }

   public function onlinePaymentsNew(Request $request)
    {


       $start_date = $request->input('start_date') ? date('Y-m-d', strtotime($request->input('start_date'))) : date('Y-m-d');
       $end_date = $request->input('end_date') ? date('Y-m-d', strtotime($request->input('end_date'))) : date('Y-m-d');

       /*$start_date = '2016-05-25';
       $end_date = '2016-05-29';*/
       $end_date = date('Y-m-d', strtotime('+1 days', strtotime($end_date)));

       $data = [
                  
                 'filter' => [
                    'created_at_min' => $start_date,
                    'created_at_max' => $end_date
                    ]
                ];


        $fetchedOrders =  Woocommerce::get('orders',$data);
        $fetchedOrders = json_encode($fetchedOrders);
        $fetchedOrders = json_decode($fetchedOrders);
        $orders = array();
        foreach($fetchedOrders->orders as $fetched_order)
        {
            
            $order = new WebOrder();
            $order->transaction_id = $fetched_order->id;
            if($fetched_order->status=='failed' || $fetched_order->status=='cancelled')
            {
                $note_url = "orders/".$fetched_order->id."/notes";

                $order_notes =  Woocommerce::get($note_url);
                //$order_notes = $order_notes->orderBy('id');
             /*   if($fetched_order->id==4731)
                {
                  //$aaa =  array_pop($order_notes);

                dd($order_notes);

                }*/
                $fetchedOrders = json_encode(array_pop($order_notes['order_notes']));
                $fetchedOrders = json_decode($fetchedOrders);
                $order->note = $order_notes['order_notes'];//$fetchedOrders->note;

                if($fetched_order->payment_details->method_id=='icici')
                {
                $nteArr = explode('$msg$', $fetchedOrders->note);
                
                $order->note = array_pop($nteArr);
                }
            }
            else
                $order->note = "";

            $order->order_date = date('Y-m-d H:i:s', strtotime($fetched_order->created_at));

            $order->post_status = $fetched_order->status;
            $order->currency = $fetched_order->currency;
            $order->total_amount = $fetched_order->total;
            $order->subtotal = $fetched_order->subtotal;
            $order->total_line_items_quantity = $fetched_order->total_line_items_quantity;

            $order->total_discount = $fetched_order->total_discount;
            
            $order->payment_method = $fetched_order->payment_details->method_id;
            $order->firstname = $fetched_order->billing_address->first_name;
            $order->lastname = $fetched_order->billing_address->last_name;
            $order->city = $fetched_order->billing_address->city;
            $order->state = $fetched_order->billing_address->state;
            $order->country = $fetched_order->billing_address->country;

            $order->email = trim($fetched_order->billing_address->email);
            $order->phone = trim($fetched_order->billing_address->phone);
            $lead = Lead::with('cre')
                            ->where('phone', $order->phone)
                            ->orWhere('mobile', $order->phone)
                            ->first();

            if ($lead) 
            {
                $order->lead_id = $lead->id;
            }
            if (isset($lead->cre)) {                
                $order->cre = $lead->cre->cre;
            }
            else
                $order->cre = "";

           
            $order->view_order_url = $fetched_order->view_order_url;

            $orders[] = $order;
        } 
        //dd($orders);
        return $orders;
    }
}
