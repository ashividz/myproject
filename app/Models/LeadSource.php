<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\Helper;

use DB;
use Auth;

class LeadSource extends Model
{
    protected $table = "lead_sources";

    protected $fillable = ['source_id', 'referrer_clinic', 'referrer_enquiry_no', 'sourced_by', 'remarks', 'voice_id', 'httpagent'];

    public function master()
    {
    	return $this->belongsTo(Source::class, 'source_id');
    }

    public function referrer()
    {
        return $this->belongsTo(Lead::class, 'referrer_id');
    }

    public function voice()
    {
        return $this->belongsTo(Voice::class, 'voice_id');
    }

    public static function isExistingSource($id, $source)
    {
    	$leadSource = LeadSource::where('lead_id', $id)
    				->orderby('id', 'DESC')
                    ->first();

        if ($leadSource) {
            if ($leadSource->source_id == $source) {
                return true;
            }
        }        

        return false;
    } 

    public static function saveSource($lead, $request, $i = NULL)
    {
        $leadSource = new LeadSource;
    	$leadSource->lead_id = $lead->id; 
    	$leadSource->clinic = $lead->clinic;
    	$leadSource->enquiry_no = $lead->enquiry_no; 
        $referrer_id = null;

        if ($i) {
            $leadSource->source_id = $request->source[$i];
            //$leadSource->referrer_clinic = Helper::emptyStringToNull($request->referrer_clinic[$i]);
            //$leadSource->referrer_enquiry_no = Helper::emptyStringToNull($request->referrer_enquiry_no[$i]);
            $leadSource->referrer_id = Helper::emptyStringToNull($request->referrer_id[$i]);
            $leadSource->sourced_by = trim($request->sourced_by[$i]) == '' && $request->source == 10 ? Auth::user()->employee->name : Helper::emptyStringToNull($request->sourced_by[$i]);
            $leadSource->remarks = Helper::emptyStringToNull(htmlentities($request->remark[$i]));
            
            $referrer_id = $request->referrer_id[$i];
        }
        else {
            $leadSource->source_id = $request->source;
            $leadSource->voice_id = Helper::emptyStringToNull($request->voice);
            $leadSource->referrer_id = Helper::emptyStringToNull($request->referrer_id);
            //$leadSource->referrer_clinic = Helper::emptyStringToNull($request->referrer_clinic);
            //$leadSource->referrer_enquiry_no = Helper::emptyStringToNull($request->referrer_enquiry_no);
            $leadSource->sourced_by = trim($request->sourced_by) == '' && $request->source == 10 ? Auth::user()->employee->name : Helper::emptyStringToNull($request->sourced_by);
            $leadSource->remarks = Helper::emptyStringToNull(htmlentities($request->remark));
            $referrer_id = $request->referrer_id;
        }

        if ($referrer_id) {
            $referrer = Lead::find($referrer_id);
            $leadSource->referrer_clinic = $referrer->clinic;
            $leadSource->referrer_enquiry_no = $referrer->enquiry_no;
        }   
            

        $leadSource->created_by = Auth::user()->employee->name;
    	
    	$leadSource->save();

        //Update Source in Leads Table
        Lead::updateSource($lead->id, $leadSource->source_id); 

        return true;
    }

    public static function ifSameSource($lead, $source)
    {
        $leadSource = LeadSource::where('lead_id', $lead->id)
                    ->orderBy('id', 'DESC')
                    ->first();

        if (isset($leadSource) && $leadSource->source_id == $source) {
            return true;
        }
        
        return false;
    }



    public static function getChannelDistributionBySource($source, $start_date, $end_date)
    {
        return LeadSource::select(DB::RAW('count(*) AS cnt'))
                ->whereBetween('created_at', array($start_date, $end_date))
                ->where('source_id', $source)
                ->first();
    }


    public static function referenceCount($cre = NULL, $start_date = NULL, $end_date = NULL) 
    {
        $start_date = isset($start_date) ? $start_date : date('Y/m/d 0:0:0');
        $end_date = isset($end_date) ? $end_date : date('Y/m/d 23:59:59');

        $query = LeadSource::where('source_id', '10')
                    ->whereBetween('created_at', array($start_date, $end_date));
        
        if ($cre) {
            $query = $query->where('sourced_by', $cre);
        }
        
        return $query->count();
    }

}
