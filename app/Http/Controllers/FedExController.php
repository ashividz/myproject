<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use FedEx;
use Carbon;

use App\Models\FedExTracking;
use App\Models\Shipping;
use App\Models\ShippingIntimation as Intimation;
use App\Support\SMS;
use DB;
use Session;

class FedExController extends Controller
{
    protected $accessKey;
    protected $password;
    protected $acctNum;
    protected $meterNum;
    
    public function __construct()
    {
        $this->accessKey = "VKl7pRM5gvZ3z5p7";
        $this->password  = "xPKXq3FEHpV6ljvToOEmLGiWc";
        $this->acctNum   = "692394186";
        $this->meterNum  = "109379373";  
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array(
            'menu'      =>  'shipping',
            'section'   =>  'fedex'
        );

        return view('home')->with($data);
    }

    public function getTrackings(Request $request = null)
    {   
        $start_date = isset($request->start_date) ? Carbon::parse($request->start_date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');

        $end_date = isset($request->end_date) ? Carbon::parse($request->end_date)->format('Y-m-d 23:59:59') : Carbon::now(); 

        $trackings =  FedExTracking::with(['shipping.cart.lead' => function($q) {
                            $q->select('id', 'name');
                        }])
                        ->with('returned')
                        ->whereNull('parent_id')
                        ->whereBetween('created_at', [$start_date, $end_date])
                        ->get(); //dd($trackings);
        foreach ($trackings as $tracking) {
            
            //$tracking = $this->decode($tracking);

            $tracking = $this->statusClass($tracking);
            
        }
        return $trackings;
    }

    public function sync()
    { 
        $this->syncTrackings();
        return $this->getTrackings();   
    }

    public function syncTrackings()
    {       
        
        try {
            $trackings = FedExTracking::get();
            foreach ($trackings as $tracking) {

                $tracking = $this->updateTracking($tracking);

                $shipping = Shipping::updateStatus($tracking->shipping_id, $tracking->status_detail->Code, $tracking->estimated_delivery_timestamp, $tracking->actual_delivery_timestamp);
                //dd($tracking->other_identifiers);
                if ($tracking->other_identifiers) {
                    if (isset($tracking->other_identifiers->PackageIdentifier)) {
                        # code...
                    } else {
                        foreach ($tracking->other_identifiers as $identifier) {
                            
                            if ($identifier->PackageIdentifier->Type == "RETURNED_TO_SHIPPER_TRACKING_NUMBER") {
                                $tracking = $this->saveReturnPackage($tracking, $identifier->PackageIdentifier->Value);
                                //echo ($identifier->PackageIdentifier->Value);
                            }
                        } 
                    }                   
                }
                    
            }

        } catch (Exception $e) {
         var_dump($e);
        }
    }

    public function updateTracking($tracking)
    {
        if ($tracking->status_detail && ($tracking->status_detail->Code == 'DL' || ($tracking->status_detail->Code == 'DE' && $tracking->returned))) {
            return $tracking;
        }
        $fedex= new FedEx\TrackService\Track($this->accessKey, $this->password, $this->acctNum, $this->meterNum); 
        $shipment = $fedex->getByTrackingId($tracking->id);
        $details = isset($shipment->CompletedTrackDetails) ? $shipment->CompletedTrackDetails->TrackDetails : null;
        $tracking->status_detail = isset($details->StatusDetail) ? $details->StatusDetail : null;
        $tracking->service_commit_message = isset($details->ServiceCommitMessage) ? $details->ServiceCommitMessage : null;
        $tracking->carrier_code = isset($details->CarrierCode) ? $details->CarrierCode : null;
        $tracking->carrier_description = isset($details->OperatingCompanyOrCarrierDescription) ? $details->OperatingCompanyOrCarrierDescription : null;
        $tracking->other_identifiers = isset($details->OtherIdentifiers) ? $details->OtherIdentifiers : null;
        $tracking->service = isset($details->Service) ? $details->Service : null;
        $tracking->package_weight = isset($details->PackageWeight) ? $details->PackageWeight : null;
        $tracking->shipment_weight = isset($details->ShipmentWeight) ? $details->ShipmentWeight : null;
        $tracking->package_count = isset($details->PackageCount) ? $details->PackageCount : null;
        $tracking->special_handlings = isset($details->SpecialHandlings) ? $details->SpecialHandlings : null; 
        $tracking->shipper_address = isset($details->ShipperAddress) ? $details->ShipperAddress : null; 
        $tracking->destination_address = isset($details->DestinationAddress) ? $details->DestinationAddress : null; 
        $tracking->estimated_delivery_timestamp = isset($details->EstimatedDeliveryTimestamp) ? $details->EstimatedDeliveryTimestamp : $tracking->estimated_delivery_timestamp; 
        $tracking->actual_delivery_timestamp = isset($details->ActualDeliveryTimestamp) ? $details->ActualDeliveryTimestamp : null; 
        $tracking->actual_delivery_address = isset($details->ActualDeliveryAddress) ? $details->ActualDeliveryAddress : null; 
        $tracking->delivery_location_type = isset($details->DeliveryLocationType) ? $details->DeliveryLocationType : null; 
        $tracking->delivery_signature_name = isset($details->DeliverySignatureName) ? $details->DeliverySignatureName : null; 
        $tracking->events = isset($details->Events) ? $details->Events : null;
        $tracking->save();

        $this->intimateUser($tracking);

        return $tracking;
    }

    public function saveTracking($track, $id)
    {
        try {

            $tracking = FedExTracking::find($id);
            
            if ($tracking) {
                return $tracking;
            }

            $tracking = new Tracking;
            $tracking->id = $id;
            $tracking->parent_id = $track->id;
            $tracking->cart_id = $track->cart_id;
            $tracking->save();
            $tracking = $this->updateTracking($tracking);
            return $tracking;

        } catch (QueryException $e) {
            return $e;
        }
        
    }

    private function saveReturnPackage($tracking, $id) {
        //$tracking->return_id = $id;
        //$tracking->save();

        $returnTracking = $this->saveTracking($tracking, $id);
        
        return $tracking;
    }
    
    public function modal($id)
    {
        $tracking = FedExTracking::find($id);
        //$tracking = $this->decode($tracking);
        $tracking = $this->statusClass($tracking);

        $data = array(
            'tracking'      => $tracking,
            'i'             =>  1
        );

        return view('shipping.modal.track')->with($data);
    }

    private function statusClass($tracking)
    {
        if(!isset($tracking->status_detail->Code)) {
            return false;
        }
        switch ($tracking->status_detail->Code) {                
            case 'OC':
                $tracking->status_class = 'in_progress';
                break;
            case 'PU':
                $tracking->status_class = 'picked_up';
                break;
            case 'FD':
                $tracking->status_class = 'in_transit';
                break;
            case 'DE':
                $tracking->status_class = 'exception';
                break;
            case 'DL':
                $tracking->status_class = 'delivered';
                break;
            default:
                $tracking->status_class = 'in_transit';
        }

        return $tracking;
    }

    public function intimateUser($tracking)
    {
        if (isset($tracking->events->EventType)) {
            $this->checkEventType($tracking, $tracking->events);
        } elseif ($tracking->events) {
            foreach ($tracking->events as $event) {
                $this->checkEventType($tracking, $event);
            }
        }            
    }

    private function checkEventType($tracking, $event)
    {
        if (Carbon::parse($event->Timestamp)->format('Y-m-d') <> Carbon::now()->format('Y-m-d')) {
            //return;
        }
        if ($tracking->parent_id) {
            return;
        }
        
        if ($event->EventType == 'OC') {
            $this->sendShippedIntimation($tracking, $event);
        }

        if ($event->EventType == 'OD') {
            $this->sendOutForDeliveryIntimation($tracking, $event);
        }

        if ($event->EventType == 'DL') {
            $this->sendDeliveredIntimation($tracking, $event);
        }        

    }

    private function sendShippedIntimation($tracking, $event)
    {
        $intimation = Intimation::where('tracking_id', $tracking->id)->where('event_type', 'OC')->first();

        if ($intimation) {
            return;
        }
        if (!isset($tracking->estimated_delivery_timestamp)) {
            return;
        } 
        $mobile = $tracking->shipping->cart->lead->mobile ? $tracking->shipping->cart->lead->mobile : $tracking->shipping->cart->lead->phone;   

        $message = $this->getShippedMessage($tracking, $event);
        $sms = new SMS;
        $sms_response = $sms->send($mobile, $message);
        $this->saveIntimation($tracking->id, 'OC', $message, $sms_response, false);
    }

    private function sendDeliveredIntimation($tracking, $event)
    {
        $intimation = Intimation::where('tracking_id', $tracking->id)->where('event_type', 'DL')->first();

        if ($intimation) {
            return;
        }
        $mobile = $tracking->cart->lead->mobile ? $tracking->cart->lead->mobile : $tracking->cart->lead->phone; 
        $message = $this->getDeliveredMessage($tracking, $event);
        $sms = new SMS;
        $sms_response = $sms->send($mobile, $message);
        $this->saveIntimation($tracking->id, 'DL', $message, $sms_response, false);
    }

    private function sendOutForDeliveryIntimation($tracking, $event)
    {
        $intimation = Intimation::where('tracking_id', $tracking->id)->where('event_type', 'OD')->first();

        if ($intimation) {
            return;
        }
        $mobile = $tracking->cart->lead->mobile ? $tracking->cart->lead->mobile : $tracking->cart->lead->phone; 
        $message = $this->getOutForDeliveryMessage($tracking, $event);
        $sms = new SMS;
        $sms_response = $sms->send($mobile, $message);
        $this->saveIntimation($tracking->id, 'OD', $message, $sms_response, false);
    }

    private function saveIntimation($id, $event_type, $message, $sms_response, $email_sent)
    {
        $intimation = new Intimation;
        $intimation->tracking_id = $id;
        $intimation->event_type = $event_type;
        $intimation->message = $message;
        $intimation->sms_response = $sms_response;
        $intimation->email_sent = $email_sent;
        $intimation->save();
    }

    /*private function sendShippedSMS($tracking, $event)
    {
        

        //Send SMS;
        $sms = new SMS;
        return $sms->send('9650306590', $message);
    }*/
    private function getShippedMessage($tracking, $event)
    {
        $message = '';

        if (isset($tracking->cart->lead)) {
            $message .= 'Namaste '.$tracking->cart->lead->name.'! ';
        }
        
        $message .= 'Your Order from Dr Shikhas Nutri-health with FedEx Tracking Id '. $tracking->id.' has been shipped';

        if ($tracking->estimated_delivery_timestamp) {
            $message .= ' and is expected to be delivered by '. Carbon::parse($tracking->estimated_delivery_timestamp)->format('D, jS M h:i A');
        } else {
            $message .= '.';
        }

        return $message;
    }
    
    private function getOutForDeliveryMessage($tracking, $event)
    {
        $message = 'Namaste '.$tracking->cart->lead->name.'! Your Order from Dr Shikhas Nutri-health with FedEx Tracking Id '. $tracking->id.' is out for delivery.';

        return $message;
    }

    private function getDeliveredMessage ($tracking, $event)
    {
        $message = 'Namaste '.$tracking->cart->lead->name.'! Your Order from Dr Shikhas Nutri-health with FedEx Tracking Id '. $tracking->id.' was delivered on '. Carbon::parse($event->Timestamp)->format('jS M, Y h:i A');
        return $message;
    }

    /*private function sendDeliveredSMS($mobile, $message)
    {
        $sms = new SMS;
        return $sms->send('9650306590', $message);
    }

    private function sendOutForDeliverySMS($tracking)
    {
        

        //Send SMS;
        $sms = new SMS;
        $mobile = $tracking->cart->lead->mobile ? $tracking->cart->lead->mobile : $tracking->cart->lead->phone;
        return $sms->send('9650306590', $message);
    }*/

    public function store(Request $request) 
    {
        $tracking = FedExTracking::create($request->all());
        $tracking = $this->updateTracking($tracking);

        Session::flash('message', 'Tracking Created');
        Session::flash('status', 'success');
        return back();
    }
}
