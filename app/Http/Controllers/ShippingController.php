<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use FedEx;
use Carbon;

use App\Models\Shipping;
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

    public function getShippings(Request $request = null)
    {   
        $start_date = isset($request->start_date) ? Carbon::parse($request->start_date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');

        $end_date = isset($request->end_date) ? Carbon::parse($request->end_date)->format('Y-m-d 23:59:59') : Carbon::now(); 

        $shippings =  Shipping::with('cart.lead', 'invoices', 'carrier')
                        ->whereBetween('created_at', [$start_date, $end_date])
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

    public function store(Request $request) 
    {
        $shipping = Shipping::create($request->all());
        //$shipping = $this->updateTracking($shipping);

        if ($request->carrier_id == 1) {
            $tracking = FedExTracking::store($shipping);
        }

        Session::flash('message', 'Tracking Created');
        Session::flash('status', 'success');
        return back();
    }
}
