<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use App\Models\LeadSource;

use DB;
use Carbon;

class ReferenceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ReferenceController
    |--------------------------------------------------------------------------
    |
    | This controller is for reporting feature for reference collected by nutritionist.    
    | This also includes conversions.
    |
    */

    public $daterange;
    public $start_date;
    public $end_date;
    protected $waved_off_days = 30; //This is number of days after which reference cen be added to take into account genuine references

    public function __construct()
    {
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y-m-d 0:0:0', strtotime($this->daterange[0])) : date("Y-m-01 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y-m-d 23:59:59', strtotime($this->daterange[1])) : date('Y-m-d 23:59:59');        
    } 

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leads = Lead::getReferenceLeads($this->start_date, $this->end_date);

        //$end_date = date('Y-m-d H:i:s',strtotime($this->end_date));
        //$this->start_date = "2016-11-01";

        $summaries = LeadSource::with(['lead.patient.fees' => function($q) {
                         $q->whereBetween('created_at', [$this->start_date, $this->end_date]);
                        //->where('source_id', 10);
                    }])
                    ->whereBetween('created_at', [$this->start_date, $this->end_date])
                    ->where('source_id', 10)
                    //->where('sourced_by', 'Deepti Gupta')
                    //->groupBy('lead_id')
                    //->limit(9)
                    ->get(); 
        //dd($summaries->pluck('lead.patient.fees'));
        $summaries = $summaries->groupBy('sourced_by');

        foreach ($summaries as $key => $value) {
            
            $filtered = $value->filter(function ($value) {
                if ( isset($value->lead->patient->fees) ) {
                    foreach ($value->lead->patient->fees as $fee) {
                        if ( Carbon::parse($value->created_at)->subDays($this->waved_off_days)->lte($fee->created_at) )
                            return true;
                    }
                }
                return false;
            });

            $value->conversions = $filtered->count();//;$value->where('lead.patient.id', '>', 0)->pluck('lead.patient');
            $value->sourced_by = $key;
            $value->patients = LeadSource::
                                whereHas('lead.patient.fees', function($q) {
                                    $q->whereBetween('created_at', [$this->start_date, $this->end_date]);
                                        //->where('source_id', 10);
                                })
                                //->with('lead.patient.fees')
                                ->where('sourced_by', $key)
                                ->where('source_id', 10)
                                ->get();
                                //dd($value->patients);
                                //dd($value);
            $value->patients = $value->patients->filter(function ($value) {
                    if ( isset($value->lead->patient->fees) ) {
                        foreach ($value->lead->patient->fees as $fee) {
                            if ( Carbon::parse($value->created_at)->subDays($this->waved_off_days)->lte($fee->created_at) )
                                return true;
                        }
                    }
                    return false;
                })
            ->count();
        }

        //dd($summaries);

        /*$summaries = DB::table('lead_sources AS s')
                    ->select('sourced_by', DB::RAW('COUNT(*) AS leads, COUNT(CASE WHEN f.entry_date >= s.created_at THEN f.entry_date END) AS conversions,
                        COUNT(CASE WHEN f.entry_date >= s.created_at and f.entry_date<="'.$end_date.'" THEN f.entry_date END) AS sameDateRangeConversion
                        '))
                    ->leftjoin('patient_details AS p', 'p.lead_id', '=', 's.lead_id')

                    ->leftJoin(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id = B.patient_id)) AS f'), function($join) {
                        $join->on('p.id', '=', 'f.patient_id');
                    })
                    ->where('s.source_id', '10')
                    ->whereBetween('s.created_at', array($this->start_date, $this->end_date))
                    ->groupBy('sourced_by')
                    ->orderBy('conversions', 'DESC')
                    ->get();*/

        

        $referrers = DB::table('lead_sources AS s')
                    ->select('m.id', 'm.name', 'pd.nutritionist', 'referrer_id', DB::RAW('COUNT(*) AS leads, COUNT(CASE WHEN f.entry_date >= s.created_at THEN f.entry_date END) AS conversions'))
                    ->leftjoin('patient_details AS p', 'p.lead_id', '=', 's.lead_id')

                    ->leftJoin(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id = B.patient_id)) AS f'), function($join) {
                        $join->on('p.id', '=', 'f.patient_id');
                    })
                    ->join('marketing_details AS m', 'm.id', '=', 'referrer_id')
                    ->join('patient_details AS pd', 'pd.lead_id', '=', 'm.id')
                    ->where('s.source_id', '10')
                    ->whereBetween('s.created_at', array($this->start_date, $this->end_date))
                    ->groupBy('referrer_id')
                    ->orderBy('conversions', 'DESC')
                    ->get();

        $data = array(
            'leads'         =>  $leads,
            'menu'          => 'reports',
            'section'       => 'reference',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'summaries'     =>  $summaries,
            'total_leads'   => '0',
            'total_conversions' => '0',
            'total_conversions_same_range' => '0',
            'referrers'     =>  $referrers
        );

        return view('home')->with($data);   
    }

    public function platinum()
    {
        $platinums = DB::table('lead_sources AS s')
                    ->select('m.id', 'm.name', 'referrer_id', DB::RAW('COUNT(*) AS leads, COUNT(CASE WHEN f.entry_date >= s.created_at THEN f.entry_date END) AS conversions'))
                    ->leftjoin('patient_details AS p', 'p.lead_id', '=', 's.lead_id')

                    ->leftJoin(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id = B.patient_id)) AS f'), function($join) {
                        $join->on('p.id', '=', 'f.patient_id');
                    })
                    ->join('marketing_details AS m', 'm.id', '=', 'referrer_id')
                    ->where('s.source_id', '10')
                    //->whereBetween('s.created_at', array($this->start_date, $this->end_date))
                    ->groupBy('referrer_id')
                    ->orderBy('conversions', 'DESC')
                    ->get();

        $data = array(
            'menu'          => 'reports',
            'section'       => 'platinum',
            'platinums'     =>  $platinums
        );

        return view('home')->with($data);   
    }

    public function reference()
    {
            $leads = Lead::getReferenceLeads($this->start_date, $this->end_date);

            $international = DB::table('lead_sources AS s')
                    ->select('m.id', 'm.name', 'referrer_id' ,'m.country','m.state', DB::RAW('COUNT(*) AS leads, COUNT(CASE WHEN f.entry_date >= s.created_at THEN f.entry_date END) AS conversions'))
                    ->leftjoin('patient_details AS p', 'p.lead_id', '=', 's.lead_id')

                    ->leftJoin(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id = B.patient_id)) AS f'), function($join) {
                        $join->on('p.id', '=', 'f.patient_id');
                    })
                    ->join('marketing_details AS m', function($join){
                        $join->on('m.id', '=', 'referrer_id')
                        ->where('m.country' , '<>' , 'IN')->where('m.country' , '<>' , '');
                    })
                    ->where('s.source_id', '10')
                    ->whereBetween('s.created_at', array($this->start_date, $this->end_date))
                    ->groupBy('referrer_id')
                    ->orderBy('conversions', 'DESC')
                    ->get();

                    

            $panindia = DB::table('lead_sources AS s')
                    ->select('m.id', 'm.name', 'referrer_id' ,'m.country','m.city', DB::RAW('COUNT(*) AS leads, COUNT(CASE WHEN f.entry_date >= s.created_at THEN f.entry_date END) AS conversions'))
                    ->leftjoin('patient_details AS p', 'p.lead_id', '=', 's.lead_id')

                    ->leftJoin(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id = B.patient_id)) AS f'), function($join) {
                        $join->on('p.id', '=', 'f.patient_id');
                    })
                    ->join('marketing_details AS m', function($join){
                        $join->on('m.id', '=', 'referrer_id')
                        ->where(function ($query)
                            {
                               $query->where('m.country' ,'=','IN')
                               ->orWhere('m.country' , '=' , '');

                            })->where('m.state' ,'<>' , 'IN.07');
                    })
                    ->where('s.source_id', '10')
                    ->whereBetween('s.created_at', array($this->start_date, $this->end_date))
                    ->groupBy('referrer_id')
                    ->orderBy('conversions', 'DESC')
                    ->get();

                    

            $delhincr = DB::table('lead_sources AS s')
                    ->select('m.id', 'm.name', 'referrer_id' ,'m.country','m.state', DB::RAW('COUNT(*) AS leads, COUNT(CASE WHEN f.entry_date >= s.created_at THEN f.entry_date END) AS conversions'))
                    ->leftjoin('patient_details AS p', 'p.lead_id', '=', 's.lead_id')

                    ->leftJoin(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id = B.patient_id)) AS f'), function($join) {
                        $join->on('p.id', '=', 'f.patient_id');
                    })
                    ->join('marketing_details AS m', function($join){
                        $join->on('m.id', '=', 'referrer_id')
                        ->where('m.state' ,'=' , 'IN.07');
                    })
                    ->where('s.source_id', '10')
                    ->whereBetween('s.created_at', array($this->start_date, $this->end_date))
                    ->groupBy('referrer_id')
                    ->orderBy('conversions', 'DESC')
                    ->get();

            $data = array(
            'leads'         =>  $leads,
            'menu'          => 'reports',
            'section'       => 'regionwise_reference',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'panindia'     =>  $panindia,
            'international'     =>  $international,
            'total_leads'   => '0',
            'total_conversions' => '0',
            'total_conversions_same_range' => '0',
            'delhincr'     =>  $delhincr
        );

        return view('home')->with($data);  

                
    }



    public function churnLeads()
    {
       $users = User::getUsersByRole('cre');

        $leads = Lead::getReferenceLeads($this->start_date, $this->end_date);
        
        
       // dd($leads);

        $data = array(
            'menu'          =>  'reports',
            'section'       =>  'churn',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'users'         =>  $users,
            'leads'         =>  $leads
        );

        return view('home')->with($data);
    }
}
