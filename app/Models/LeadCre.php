<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\Lead;

class LeadCre extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    protected $table = "lead_cre";

    protected $dates = ['deleted_at'];
    
    protected $fillable = ['cre', 'start_date'];


    public static function saveCre($lead, $cre=null)
    {
    	try 
    	{
    		$leadCre = new LeadCre;
	    	$leadCre->lead_id = $lead->id;
	    	//$leadCre->clinic = $lead->clinic;
	    	//$leadCre->enquiry_no = $lead->enquiry_no;
            $leadCre->user_id = isset($cre) ? Auth::user()->id : "" ;
            $leadCre->cre = trim($cre) <> "" ? $cre : Auth::user()->employee->name;
            $leadCre->start_date = date('Y/m/d');
            $leadCre->created_by = Auth::user()->employee->name;
	    	$leadCre->save();

            Lead::updateCre($lead->id, $leadCre->cre);
	    	
            return $leadCre;
    	} 
    	catch (\Illuminate\Database\QueryException $e) 
    	{
    		dd($e);
            return false;
    	}	    	
    }

    public static function ifMultipleCreOnSameDate($lead)
    {
        $leadCre = LeadCre::where('lead_id', $lead->id)
                    ->orderBy('id', 'DESC')
                    ->first();
                    //dd($leadCre);

        if (isset($leadCre) && date('Y/m/d', strtotime($leadCre->created_at)) == date('Y/m/d')) {
            return true;
        }
        
        return false;
    }

    public static function ifSameCre($lead, $cre)
    {
        $leadCre = LeadCre::where('lead_id', $lead->id)
                    ->orderBy('id', 'DESC')
                    ->first();

        if (isset($leadCre) && $leadCre->cre == $cre) {
            return true;
        }
        
        return false;
    }

    public static function leadCount($cre= NULL, $start_date = NULL, $end_date = NULL)
    {
        $start_date = isset($start_date) ? $start_date : date('Y/m/d 0:0:0');
        $end_date = isset($end_date) ? $end_date : date('Y/m/d 23:59:59');

        $query = LeadCre::whereBetween('created_at', array($start_date, $end_date));

        if ($cre) {
            $query = $query->where('cre', $cre);
        }
        
        return $query->count();
    }

}
