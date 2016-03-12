<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lead;
use DB;

class LeadStatus extends Model
{
    protected $table = 'lead_status';
    protected $fillable = ['clinic', 'enquiry_no', 'status'];

    public function master()
    {
        return $this->belongsTo(Status::class, 'status');
    }

    public function getPipelinesByStatus($start_date, $end_date, $status)
    {
    	return DB::select("SELECT calDay as `date`, m.id, m.clinic, m.enquiry_no, m.name, d.name AS cre, remarks, callback, l.status FROM
					(SELECT cast(:start_date + interval `day` day as date) calDay FROM days365
					WHERE  cast(:start_date + interval `day` day as date) <= :end_date) calendar
					LEFT JOIN call_dispositions d ON DATE_FORMAT(callback, '%y-%m-%d') = calDay
					JOIN marketing_details m ON m.clinic=d.clinic AND m.enquiry_no = d.enquiry_no
					JOIN (SELECT clinic, enquiry_no, status FROM lead_status A
					WHERE id = (SELECT MAX(id) FROM lead_status B WHERE A.clinic=B.clinic AND A.enquiry_no=B.enquiry_no)) s
					ON s.clinic=m.clinic AND s.enquiry_no=m.enquiry_no
					JOIN m_lead_status l ON l.id = s.status
					WHERE d.callback = (SELECT MAX(callback) FROM call_dispositions A WHERE A.clinic=d.clinic AND A.enquiry_no=d.enquiry_no AND disposition=:status) 
					GROUP BY clinic, enquiry_no
					ORDER BY callback",
					[$start_date, $start_date, $end_date, $status]);
    }

    public static function setStatus($id, $status)
    {
        $lead = Lead::find($id);

        //$lastStatus = LeadStatus::getLastStatus($lead);
        
        //if ($status > $lastStatus->status) {
            LeadStatus::saveStatus($lead, $status);
        //}
    }

    public static function saveStatus($lead, $status)
    {
        if($status > 0) {
            $leadStatus = new LeadStatus;

            $leadStatus->lead_id = $lead->id;
            $leadStatus->clinic = $lead->clinic;
            $leadStatus->enquiry_no = $lead->enquiry_no;
            $leadStatus->status = $status;
            $leadStatus->save();

            Lead::updateStatus($lead->id, $status); //Update Status in Leads Table

            return $leadStatus;
        }
    }

    public static function getLastStatus($lead)
    {
    	return LeadStatus::select('status')
    				->where('lead_id', '=', $lead->id)
    				->orderBy('id', 'DESC')
    				->first();
    	//dd($d);
    }

    public static function ifSameStatus($lead, $status)
    {
        $leadStatus = LeadStatus::where('lead_id', $lead->id)
                    ->orderBy('id', 'DESC')
                    ->first();

        if (isset($leadStatus) && $leadStatus->status == $status) {
            return true;
        }
        
        return false;
    }

     public static function hotCount($cre, $start_date = NULL, $end_date = NULL) 
    {
        $start_date = isset($start_date) ? $start_date : date('Y/m/d 0:0:0');
        $end_date = isset($end_date) ? $end_date : date('Y/m/d 23:59:59');
    }
}
