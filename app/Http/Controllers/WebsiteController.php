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

    public function onlinePaymentsNew(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $orders = WebOrder::with('orderMetas')
                    ->whereBetween('post_date', array($start_date, $end_date))
                    ->where('post_type', 'shop_order')
                    ->orderBy('post_date', 'DESC')
                    ->get();
               //dd($orders);     
        foreach ($orders as $order) {
            //dd($order->orderMeta);
            $order->transaction_id = $order->ID;
            $order->order_date = $order->post_date;
            foreach ($order->orderMetas as $orderMeta) {
                if($orderMeta->meta_key=='_billing_phone')
                {
                    $phone = Helper::properMobile($orderMeta->meta_value);
                   $order->phone =  $phone;
                   $lead = Lead::with('cre')
                        ->where('phone', $phone)
                        ->orWhere('mobile', $phone)
                        ->first();

                    if ($lead) {
                        $order->lead_id = $lead->id;
                    }
                    if (isset($lead->cre)) {                
                        $order->cre = $lead->cre->cre;
                    }

                }

                if($orderMeta->meta_key=='_billing_first_name')
                   $order->firstname =  $orderMeta->meta_value;

                if($orderMeta->meta_key=='_billing_last_name')
                   $order->lastname =  $orderMeta->meta_value;

                if($orderMeta->meta_key=='_order_currency')
                   $order->currency =  $orderMeta->meta_value;

                if($orderMeta->meta_key=='_billing_country')
                   $order->country =  $orderMeta->meta_value;

                if($orderMeta->meta_key=='_billing_state')
                   $order->state =  $orderMeta->meta_value;

                if($orderMeta->meta_key=='_billing_city')
                   $order->city =  $orderMeta->meta_value;

                if($orderMeta->meta_key=='_payment_method')
                   $order->payment_method =  $orderMeta->meta_value;

                if($orderMeta->meta_key=='_payment_method_title')
                   $order->payment_method_title =  $orderMeta->meta_value;

                if($orderMeta->meta_key=='_order_total')
                   $order->total_amount =  $orderMeta->meta_value;
            }

          //dd($order);
        }
        return $orders;

        /*$orders = DB::connection('mysql3')
            ->table('wp_posts')
            ->join('paymentprocessors as pp', 'pp.pp_id', '=', 'o.pp_id')
            ->whereBetween('order_date', array($start_date, $end_date))
            ->where('post_type','shop_order')
            ->orderBy('post_date', 'DESC')
            ->get(array('ID','guid','post_modified', 'post_date','post_status', 'firstname', 'lastname', 'o.status AS payment_status' , 'country', 'city', 'pp_name AS payment_method', 'currency', 'total_amount', 'message'));
            //var_dump($orders);
        foreach ($orders as $order) {
        $ordermeta = DB::connection('mysql3')
            ->table('wp_postmeta')
            ->where('order_id',$order->ID)
            ->get(array('_billing_first_name', '_billing_last_name', '_billing_phone as phone', '_order_currency' , '_billing_country', '_billing_state','_billing_city', '_payment_method AS payment_method','_payment_method_title AS payment_method_title', 'currency', '_order_total as total_amount'));
            //var_dump($orders);

        }*/
    }

}
