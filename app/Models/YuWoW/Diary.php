<?php

namespace App\Models\YuWoW;

use Illuminate\Database\Eloquent\Model;

use DB;

class Diary extends Model
{
    protected $table = 'yuwow_alpha_1_0.dt_cust_health_diary';

    public function user()
    {
    	return $this->belongsTo(User::class, 'dch_cust_id');
    }

    public static function getFeedbacks($start_date , $end_date)
    {
         return DB::table('yuwow_alpha_1_0.dt_cust_health_diary')
                ->join('yuwow_alpha_1_0.users', 'yuwow_alpha_1_0.users.Id', '=', 'dt_cust_health_diary.dch_cust_id')
                ->join('marketing_details','marketing_details.email', '=', 'yuwow_alpha_1_0.users.user_email')
                ->join('patient_details','patient_details.lead_id', '=', 'marketing_details.id')
                ->whereBetween('dch_date',array($start_date,$end_date))
                ->select('dch_date as date','patient_details.nutritionist as nutritionist','dch_health_diary as remark','patient_details.id as patient_id','marketing_details.name as name')                    
                ->get();
    }
}
