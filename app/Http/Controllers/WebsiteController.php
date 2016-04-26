<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Lead;
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
}