<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Cart;

class CouponController extends Controller
{
    public function validateCoupon(Request $request)
    {
        if (!$request) {
            return 0;
        }

        $percentage = 0;

        $cart = Cart::find($request->cart_id);

        if (!$cart) {
            return $percentage;
        }

        
        $url = 'http://api.nutrihealthsystems.com/coupon/validate';
        $id = 'p951be';
        $key ='847f7c49183fecbb32024b37871ad51d';
        $code =  $request->coupon;
        /*$phone = $cart->lead->phone;
        $email = $cart->lead->email;
        $product = $request->product_id;*/
        $time = time();
        $token = md5($time.$key);
       
        $params = array('id' => $id, 'token' => $token,'code'=>$code);


        $postData = '';
        //create name value pairs seperated by &
        foreach($params as $k => $v) 
        { 
            $postData .= $k . '='.$v.'&'; 
        }

        $postData = rtrim($postData, '&');
     
        $ch = curl_init();   
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HEADER, false); 
        curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);     
        $output=curl_exec($ch); 
        curl_close($ch);

        $output = json_decode($output);

        if ($output->valid) {
            $percentage = $output->percentage;
        }
        return $percentage;
    }
}