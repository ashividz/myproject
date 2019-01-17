<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use LeadSource;
use Lead;

use DB;
use Auth;

class ProductFee extends Model
{
   protected $table = "product_fee";
   protected $fillable = [
        'patient_id',
        'cart_id'
    ];

    public static function store($cart , $product , $patient)
    {
        $lfee = ProductFee::where('patient_id', $patient->id)->orderBy('end_date', 'desc')->first();

        $fee = ProductFee::firstOrNew(['cart_id' => $cart->id]);
        //$fee = new Fee;

        $fee->patient_id = $fee->patient_id ? : $patient->id;
        $fee->cart_id = $fee->cart_id ? : $cart->id;
        $fee->entry_date = Carbon::now();
        $fee->name = $cart->lead->name;
        $fee->currency_id = $cart->currency_id;

        if ($fee->start_date) {

            $fee->start_date = $fee->start_date;

        } elseif ($lfee && $lfee->end_date >= Carbon::now()->format('Y-m-d')) {

            $fee->start_date = Carbon::parse($lfee->end_date)->addDays(1);

        } else {
            $fee->start_date = Carbon::now()->addDays(1);
        }
       
        $fee->end_date = Carbon::parse($fee->start_date)->addDays($product->productduration);
        $fee->cre = $cart->cre->employee->name;
        $fee->cre_id = $cart->cre_id;
        $fee->source_id = $cart->source_id;
        $fee->total_amount = $cart->getProductPaidAmount();
        
        //$fee->valid_months = 
        $fee->duration = $product->productduration;
        $fee->created_by = Auth::id();
        $fee->save();
        LeadStatus::saveStatus($patient->lead, 5);
        return $fee;

    }


    public static function conversionCountBySource($source, $start_date, $end_date)
    {
        return ProductFee::where('source_id', $source)
                    ->whereBetween('entry_date', array($start_date, $end_date))
                    ->get();
    }

    public static function conversionCountByDate($source, $start_date, $end_date)
    {
        

        $users = Lead::with('patient')
                       ->where('source_id' , $source)
                       ->whereBetween('created_at', array($start_date, $end_date))
                       ->get();
        $lead = [];
        foreach ($users as $user) {
            if(!$user->patient) {
               continue;
            }
            else
            {
                $lead[] = $user->patient->id;
            }   
        }

        return ProductFee::whereIn('patient_id' , $lead)
                    ->where('source_id' , $source)
                    ->whereBetween('entry_date', array($start_date, $end_date))
                    /*->with(['lead.patient.fee' => function ($query) use($start_date , $end_date ) {
                                $query->whereBetween('created_at', array($start_date, $end_date));
                            }])*/
                    ->get(); 
            
                      

    }
}