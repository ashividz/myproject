<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use DB;
use Auth;

class Fee extends Model
{
    
    protected $table = "fees_details";

    protected $fillable = [
        'patient_id',
        'cart_id'
    ];

    public function getDates()
    {
       return ['entry_date', 'start_date', 'end_date','created_at', 'updated_at'];
    }

    public function patient()
    {
    	return $this->belongsTo(Patient::class);
    }

    public function source()
    {
    	return $this->belongsTo(Source::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function cre()
    {
        return $this->belongsTo(User::class, 'cre_id');
    }

    public static function conversionCount($cre = NULL, $start_date = NULL, $end_date = NULL) 
    {
        $start_date = isset($start_date) ? $start_date : date('Y/m/d 0:0:0');
        $end_date = isset($end_date) ? $end_date : date('Y/m/d 23:59:59');

        $query = Fee::distinct('patient_id')->whereBetween('created_at', array($start_date, $end_date));
                
        if ($cre) {
            $query = $query->where('cre', $cre);
        }
        
        return $query->count('patient_id');
    }

    public static function amount($cre = NULL, $start_date = NULL, $end_date = NULL) 
    {
        $start_date = isset($start_date) ? $start_date : date('Y/m/d 0:0:0');
        $end_date = isset($end_date) ? $end_date : date('Y/m/d 23:59:59');

        $query = Fee::whereBetween('created_at', array($start_date, $end_date));
                
        if ($cre) {
            $query = $query->where('cre', $cre);
        }
        
        return $query->sum('total_amount');
    }

    /*public static function programStatus($id)
    {
    	$lead = Lead::with('patient')
    				->find($id);

    	$fee = Fee::where('clinic', $lead->clinic)
    				->where('registration_no', $lead->registration_no)
    				->orderBy('end_date', 'DESC')
    				->first();

    	if($fee) {
    		

	    	$start_date = $fee->start_date;
			$end_date = $fee->end_date;
			$now = date('Y-m-d') > $end_date ? $end_date : date('Y-m-d');

			$days = floor((strtotime(date($now)) - strtotime($start_date))/(60*60*24));
			$daysLeft = 0;
			$totalDays = floor((strtotime($end_date) - strtotime($start_date))/(60*60*24));
			if ($totalDays <> 0) {
			 	$progressPercentage = floor((($days)/$totalDays)*100);
			 } 

			$message = "<div class='alert ";

			if ($start_date > date('Y-m-d')) {
				$message .= "alert-danger' role='alert'><span class='fa fa-warning'></span>";
				$message .= "<span style='vertical-align:super'>";
				$message .= " Program has not started.";
				$message .= "</span>";
			}
			elseif ($end_date < date('Y-m-d')) 
			{
				$message .= "alert-danger' role='alert'><span class='fa fa-warning'></span>";
				$message .= "<span style='vertical-align:super'>";
				$message .= " Program has expired.";
				$message .= "</span>";
			}
			elseif ($end_date == date('Y-m-d')) 
			{
				$message .= "alert-warning' role='alert'><span class='fa fa-info-circle'></span>";
				$message .= "<span style='vertical-align:super'>";
				$message .= " Program will expire today.";
				$message .= "</span>";
			}
			elseif ($end_date > date('Y-m-d'))
			{
				$daysLeft = floor((strtotime($end_date) - strtotime(date('Y-m-d')))/(60*60*24));
				$message .= "alert-success' role='alert'><span class='fa fa-info-circle'></span>";
				$message .= "<span style='vertical-align:super'>";
				$message .= " Program will expire in " . $daysLeft;		
				$message .= ($daysLeft == 1) ? " day." : " days.";
				$message .= "</span>";
			}
			else
			{
				$message .= "alert-danger' role='alert'><span class='fa fa-warning'></span>";
				$message .= "<span style='vertical-align:super'>";
				$message .= " There is something wrong with your End Date!";
				$message .= "</span>";
			}
			$message .= "</div>";

			return $message;
		}
    }*/

    public static function conversionCountBySource($source, $start_date, $end_date)
    {
    	return Fee::where('source_id', $source)
    				->whereBetween('entry_date', array($start_date, $end_date))
    				->get();
    }

    public static function conversionAmountBySource($source, $start_date, $end_date)
    {
    	return Fee::where('source_id', $source)
    				->whereBetween('entry_date', array($start_date, $end_date))
    				->sum('total_amount');
    }

    public static function conversions($start_date, $end_date, $source = NULL, $sourced_by = NULL)
    {
        $query =  Fee::join('patient_details AS p', 'fees_details.patient_id', '=', 'p.id')
                ->join('marketing_details AS m', 'm.id', '=', 'p.lead_id');

        if ($source) 
        {
            $query->join('lead_sources AS s', 'm.id', '=', 's.lead_id')->where('s.source_id', $source);
            
            if ($sourced_by) {
                $query->where('s.sourced_by', $sourced_by);
            }
        }
                
        return  $query->whereBetween('fees_details.entry_date', array($start_date, $end_date))
                ->get();
    }

    public static function updateFee($request)
    {
        $fee = Fee::find($request->id);
        //dd($fee);

        if($fee)
        {
            $fee->receipt_no = $request->receipt_no;
            $fee->valid_months = $request->valid_months;
            $fee->entry_date = date('Y-m-d', strtotime($request->entry_date));
            $fee->start_date = date('Y-m-d', strtotime($request->start_date));
            $fee->end_date = date('Y-m-d', strtotime($request->end_date));
            $fee->total_amount = $request->total_amount;
            $fee->discount = $request->discount;
            $fee->source_id = $request->source_id;
            $fee->cre = $request->cre;
            $fee->updated_by = Auth::user()->employee->name;
            $fee->save();
            return "Sucessfully updated";
        }
         return "Error";
    }

    
    public static function store($cart, $patient)
    {
        $fee = Fee::firstOrNew(['cart_id' => $cart->id]);
        //$fee = new Fee;

        $fee->patient_id = $fee->patient_id ? : $patient->id;
        $fee->cart_id = $fee->cart_id ? : $cart->id;
        $fee->entry_date = Carbon::now();
        $fee->name = $cart->lead->name;
        $fee->start_date = $fee->start_date ? $fee->start_date : Carbon::now()->addDays(1);
        $fee->end_date = Carbon::parse($fee->start_date)->addDays($cart->duration);
        $fee->cre = $cart->cre->employee->name;
        $fee->cre_id = $cart->cre_id;
        $fee->source_id = $cart->source_id;
        $fee->total_amount = $cart->getDietPaidAmount();
        //$fee->valid_months = 
        $fee->duration = $cart->duration;
        $fee->created_by = Auth::id();
        $fee->save();

        return $fee;

    }
    /*public static function store($patient, $payment)
    {
        $duration = CartProduct::getDietDuration($payment->cart_id, 1);         

        if ($duration) {
            $start_date = Carbon::now()->addDay(1);
            $end_date = Carbon::now()->addDay($duration+1);
            $today    = Carbon::now();

            $lastFee = Fee::where('cart_id', $payment->cart_id)->first();
            $existingFee = Fee::where('patient_id',$patient->id)
                        ->havingRaw('max(end_date)')
                        ->first();

            if ($lastFee) {
                $start_date = $lastFee->start_date;
                $end_date = $lastFee->end_date;
            }
            else if($existingFee &&  $existingFee->end_date >= $today){
                $start_date = $existingFee->end_date->addDay(1);
                $end_date   = $existingFee->end_date->addDay($duration+1);
            }

            $cre = User::find($payment->cart->cre_id);

            $fee = new Fee;
            $fee->patient_id        = $patient->id;
            $fee->cart_id           = $payment->cart_id;
            $fee->currency_id       = $payment->cart->currency_id;
            $fee->total_amount      = $payment->amount;
            $fee->payment_mode      = $payment->payment_mode_id;
            $fee->entry_date        = $payment->created_at;
            $fee->start_date        = $start_date;
            $fee->end_date          = $end_date;
            $fee->cre               = $cre ? $cre->employee->name : '';
            $fee->cre_id            = $payment->cart->cre_id;
            $fee->source_id         = $payment->cart->source_id;
            $fee->duration          = $duration;
            $fee->created_by        = Auth::id();
            $fee->save();
            LeadStatus::saveStatus($patient->lead, 5);            
        }
        
    }*/
}
