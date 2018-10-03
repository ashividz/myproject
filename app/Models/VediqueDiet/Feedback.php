<?php

namespace App\Models\VediqueDiet;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lead;
use App\Models\Patient;
use DB;

class Feedback extends Model
{
    protected $connection = 'VediqueDiet';
    protected $table = 'Feedback';

    public static function getFeedbacks($start_date , $end_date)
    {
        return Feedback::join('marketing_details','marketing_details.email', '=', 'email')
                ->join('patient_details','patient_details.lead_id', '=', 'marketing_details.id')
                ->whereBetween('date',array($start_date,$end_date))
                ->select('date as date','patient_details.nutritionist as nutritionist','feedback as remark','patient_details.id as patient_id','marketing_details.name as name')                    
                ->get();
    }

    public function lead()
    {
        return $this->hasOne(Lead::class , 'email' , 'email');
    }
    
}
