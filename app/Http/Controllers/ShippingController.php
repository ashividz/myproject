<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use FedEx;
use Carbon;
use Auth;
use Mail;

use App\Models\Lead;
use App\Models\Cart;
use App\Models\Shipping;
use App\Models\EmailTemplate;
use App\Models\Email;
use App\Models\FedExTracking;
use App\Models\ShippingIntimation as Intimation;
use App\Support\SMS;
use DB;
use Session;

class ShippingController extends Controller
{
    public function index()
    {
        $data = array(
            'menu'      =>  'shipping',
            'section'   =>  'index'
        );

        return view('home')->with($data);
    }

    public function get(Request $request = null)
    {   
        $start_date = isset($request->start_date) ? Carbon::parse($request->start_date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');

        $end_date = isset($request->end_date) ? Carbon::parse($request->end_date)->format('Y-m-d 23:59:59') : Carbon::now(); 

        $shippings =  Shipping::with('cart.lead', 'carrier')
                        ->whereBetween('created_at', [$start_date, $end_date])
                        ->orderBy('id', 'desc')
                        ->get(); //dd($shippings);
        foreach ($shippings as $shipping) {
            
            //$shipping = $this->decode($shipping);

            $shipping = $this->statusClass($shipping);
            
        }
        return $shippings;
    }
    
    public function modal($id)
    {
        $shipping = Tracking::find($id);
        //$shipping = $this->decode($shipping);
        $shipping = $this->statusClass($shipping);

        $data = array(
            'tracking'      => $shipping,
            'i'             =>  1
        );

        return view('shipping.modal.track')->with($data);
    }

    private function statusClass($shipping)
    {
        if(!isset($shipping->status_detail->Code)) {
            return false;
        }
        switch ($shipping->status_detail->Code) {                
            case 'OC':
                $shipping->status_class = 'in_progress';
                break;
            case 'PU':
                $shipping->status_class = 'picked_up';
                break;
            case 'FD':
                $shipping->status_class = 'in_transit';
                break;
            case 'DE':
                $shipping->status_class = 'exception';
                break;
            case 'DL':
                $shipping->status_class = 'delivered';
                break;
            default:
                $shipping->status_class = 'in_transit';
        }

        return $shipping;
    }

    public function store(Request $request, $id) 
    {
        $cart = Cart::find($id);

       

        $shipping = $cart->shippings()->create($request->all());
        //$shipping = $this->updateTracking($shipping);

        if ($request->carrier_id == 1) {
            $tracking = FedExTracking::store($shipping);
            $this->sendEmail($request, $id);
        }

         

        return $cart->shippings()->with('carrier')->get();
    }

    public function update(Request $request, $id)
    {
        $shipping = Shipping::find($id);
        $shipping->update($request->all());

        //return $shipping;
    }

    public function sendEmail($request , $id)
    {
        
       
         $cart = Cart::find($id);

         $lead = Lead::find($cart->lead_id);

        $data = array(
                'docket' => $request->tracking_id,
                'customer'  => $lead->name,
                'courier'   => 'FedEx',                
            );

        Mail::send('templates.emails.dispatch', $data, function($message) use ($lead)
        {
            $from = 'logistics@nutrihealthsystems.com';
            
            $message->to($lead->email, $lead->name)
            ->subject("Dr Shikha's NutriHealth : Shipment update")
            ->from($from, 'Nutri-Health Systems');
            
            //Add CC
            if (trim($lead->email_alt) <> '') {
                $message->cc($lead->email_alt, $name = null);
            }
        });

        return true;
<<<<<<< HEAD

    }
=======
    }

    // Kode Starts
    public function trackOrder(Request $req)
    {
        $txtOrderNo = $req->input('txtOrderNo');
        
        $orderData= array();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type:application/json',
            'Authorization:flewrfj8k23jnjwdfj23kkfe65fef'
        ));
        curl_setopt($curl, CURLOPT_URL, 'http://pp.bookmypacket.com/ERP/api/auth/v1/TrackCurrentStatus');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array('clientKey'=>"Test",'awbNumber'=>"$txtOrderNo")));//Setting post data as xml
        $result= curl_exec($curl);

        curl_close($curl);
        $orderData=json_decode($result,true);
        //dd($orderData);
        $data = array(
            'menu'          =>  'shipping',
            'orderData'     =>  $orderData,
            'section'       =>  'trackOrderForm',
            'i'             =>  1
        );
        // $data = array(
        //     'menu'          =>  'shipping',
        //     'section'       =>  'trackOrderForm',
        //     'i'             =>  1
        // );
        //die;

        return view('trackOrder')->with($data);
    }

    /*public function orderStatus(Request $req)
    {
        $txtOrderNo = $req->input('txtOrderNo');
        
        $orderData= array();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type:application/json',
            'Authorization:flewrfj8k23jnjwdfj23kkfe65fef'
        ));
        curl_setopt($curl, CURLOPT_URL, 'http://pp.bookmypacket.com/ERP/api/auth/v1/TrackCurrentStatus');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array('clientKey'=>"Test",'awbNumber'=>"$txtOrderNo")));//Setting post data as xml
        $result= curl_exec($curl);

        curl_close($curl);
        $orderData=json_decode($result,true);
        //dd($orderData);
        $data = array(
            'menu'          =>  'shipping',
            'orderData'           =>  $orderData,
            'i'             =>  1
        );

        return view('shipping/template')->with($data);
    } */
    // Kode Ends
>>>>>>> bmp
}
