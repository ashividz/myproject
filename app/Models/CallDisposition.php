<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class CallDisposition extends Model
{
    protected $table = "call_dispositions";

    protected $fillable = ['lead_id', 'disposition_id', 'name', 'remarks', 'callback', 'email', 'sms'];

    public function getDates()
    {
       return ['callback', 'created_at', 'updated_at'];
    }

    public function master()
    {
        return $this->belongsTo(Disposition::class, 'disposition_id');
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public static function filterByUser($name, $start_date, $end_date) {

    	return DB::table('marketing_details as m')
    			//->select('m.clinic, m.enquiry_no, m.name, c.remarks, c.disposition, c.callback, c.created_at')
    			->leftjoin('call_dispositions as c',  function($join)
                    {
                    	$join->on('m.clinic', '=', 'c.clinic');
                     	$join->on('m.enquiry_no', '=', 'c.enquiry_no');
                    })   
    			->where('c.name', '=', $name)
                ->whereBetween('c.created_at', array($start_date, $end_date))
                ->orderBy('c.created_at', 'DESC')
                ->get(array('m.clinic', 'm.enquiry_no', 'm.name', 'c.remarks', 'c.disposition_id', 'c.callback', 'c.created_at'));
    }

    public static function getStatusFromDisposition($disposition)
    {
        return DB::table('m_call_disposition')
                ->where('id', '=', $disposition)
                ->select('lead_status as status')
                ->first();
    }

    public static function getDispositionsByUser($user, $start_date, $end_date)
    {
        return CallDisposition::with('lead')
                ->whereBetween('created_at', array($start_date, $end_date))
                ->where('name', $user)
                ->limit(env('DB_LIMIT'))
                ->orderBy('id', 'DESC')
                ->get();
    }

    public static function getCount($user = NULL, $start_date = NULL, $end_date = NULL) 
    {
        $start_date = isset($start_date) ? $start_date : date('Y/m/d 0:0:0');
        $end_date = isset($end_date) ? $end_date : date('Y/m/d 23:59:59');

        $query = CallDisposition::whereBetween('created_at', array($start_date, $end_date));

        if ($user) {
            $query = $query->where('name', $user);
        }

        return $query->count();
    }


    public static function getHotCount($user = NULL, $start_date = NULL, $end_date = NULL) 
    {
        $start_date = isset($start_date) ? $start_date : date('Y/m/d 0:0:0');
        $end_date = isset($end_date) ? $end_date : date('Y/m/d 23:59:59');

        $query = CallDisposition::where('disposition_id', '15')
                ->whereBetween('callback', array($start_date, $end_date));
        
        if ($user) {
            $query = $query->where('name', $user);
        }

        return $query->distinct('lead_id')->count('lead_id');
    }
    
}
