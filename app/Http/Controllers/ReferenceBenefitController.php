<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;


use App\Models\User;

use App\Models\Benefit;
use App\Models\BenefitLead;
use App\Models\BenefitCart;
use App\Models\BenefitReference;
use App\Models\CartProduct;
use App\Models\Cart;
use App\Models\CartStep;
use App\Models\CartPayment;
use App\Models\Lead;
use Mail;
use Auth;
use DB;
use App\Models\Patient;
use Carbon;
use DateTime;

class ReferenceBenefitController extends Controller
{
   
    

    public function __construct(Request $request)
    {
    
        $this->nutritionist = isset($request->user) ? $request->user : Auth::user()->employee->name;
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date('Y-m-d', strtotime('-5 days'));
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y-m-d', strtotime('+5 days'));
        $this->allUsers = $request->user=='' ? true : false;
        
    } 
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getBenefits(Request $request)
    {

        $references = $request->ids;
        $count = 30;
        $lead_details = Lead::where('id',$references)
                            ->with('patient', 'patient.cfee')
                            ->first();
        $datetime1 = $lead_details->patient->cfee->end_date;
        $datetime2 = $lead_details->patient->cfee->start_date;
        $interval = $datetime1->diffInDays($datetime2);
        if($interval >= 180 && $interval < 360)
            $count = 180;
        else if($interval >= 90 && $interval < 180)
            $count = 90; 
        else if($interval >= 365)
            $count = 365;     
        $benefits = Benefit::where('duration', $count)
                            ->where('isactive', 1)
                            ->get();
        return $benefits;
   
        //$references = $request->ids;
        //$count = sizeof($references);
        //$benefits = Benefit::where('references', $count) ->get();

        //return $benefits;
    }

    public function applyBenefit(Request $request)
    {
       $benefitLead = new BenefitLead();
       $benefitLead->lead_id = $request->lead_id;
       $benefitLead->benefit_id = $request->benefit_id;
       $benefitLead->save();

       // $benefit = Benefit::find($request->benefit_id);
       if(is_null($request->product_id) || empty($request->product_id) || $request->product_id=="") 
          $this->sendCouponMail($request->lead_id, $request->reference_ids, $request->benefit_description);
       

       foreach($request->reference_ids as $reference_id)
       {
           $benefitReference = new BenefitReference();
           $benefitReference->benefit_lead_id = $benefitLead->id;
           $benefitReference->reference_id = $reference_id;
           $benefitReference->applied = true;
           $benefitReference->save();

       }

       if(isset($request->cart_id) && !is_null($request->cart_id) && !empty($request->cart_id))
       {
           $benefitCart = new BenefitCart();
           $benefitCart->benefit_lead_id = $benefitLead->id;
           $benefitCart->cart_id = $request->cart_id;
           $benefitCart->save();

           $cart = Cart::find($request->cart_id);
           $price = $amount = 0;
           $cartProduct = new CartProduct;
           $cartProduct->cart_id = $request->cart_id;
           $cartProduct->product_id = $request->product_id;
           $cartProduct->price = $price;
          
           $cartProduct->discount = $request->discount;
           $cartProduct->quantity = $request->quantity;
           $cartProduct->amount = $amount;
           $cartProduct->created_by = Auth::id();
           $cartProduct->save(); 



           $cartPayment = new CartPayment();
           $cartPayment->cart_id = $request->cart_id;
           $cartPayment->amount = $amount;
           $cartPayment->payment_method_id = 2;
           $cartPayment->date = date('Y-m-d');
           $cartPayment->remark = 'Reference Benefit';
           $cartPayment->created_by = Auth::id();
           $cartPayment->save();

           //$cart->updateAmount();

           CartStep::store($request->cart_id, 1, 3);
             //Create next OrderStep
           CartStep::nextStatus($request->cart_id);

       }

       return json_encode(['id' => $benefitLead->id]);
    }
   
    public function getReferenceBenefit(Request $request)
    {
        $referenceBenefit = BenefitReference::with('bcart.cart')
                            ->where('reference_id', $request->id)
                            ->where('applied', true)
                            ->get()->first();

                            //dd($referenceBenefit);
       if($referenceBenefit && $referenceBenefit->bcart)                     //dd($referenceBenefit->bcart);
       if($referenceBenefit->bcart->cart->state_id==2)
       {
        $referenceBenefit->applied = false;
        $referenceBenefit->save();
        
       }
     

        return $referenceBenefit;
       
    }

    public function sendCouponMail($lead_id, $reference_ids, $benefit)
    {
        $lead = Lead::find($lead_id);

        $body = "<table><tr><td><b>".$benefit."</b></td></tr>";
        $body .= "<tr><td>Lead: <a href='https://amikus/lead/".$lead_id."/viewReferences'>".$lead->id."</a></td></tr>";
        $body .= "<tr><td>Name: ".$lead->name."</td></tr>";
        $body .= "<tr><td><b>References: </b>";
        foreach($reference_ids as $reference_id)
        {
          $body .= $reference_id.", ";
        }
        $body .= "</td></tr>";
        $body .= "<tr><td>Created By: ".Auth::user()->employee->name."</td></tr>";
        $body .= "<tr><td>Created at: ".date('Y-m-d H:i:s')."</td></tr>";

        Mail::send([], [], function($message) use ($body, $lead)
        {
            $from = 'sales@nutrihealthsystems.com';
            
            $message->to('aashima.saini@nutrihealthsystems.com', 'Aashima')
            ->subject("Reference benefit coupon Request - ".$lead->id)
            ->from($from, 'Nutri-Health System' );
            
           $message->cc('nitesh@nutrihealthsystems.com ', 'Nitesh');
        

            $message->setBody($body, 'text/html');

            
        });

    }

    public function isBenefitCart(Request $request)
    {
      $benefitCart = BenefitCart::where('cart_id', $request->id)->get()->first();
      return $benefitCart;
    }
}
