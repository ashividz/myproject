<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Lead;
use App\Models\Patient;
use App\Models\Days365;
use Auth;
use DB;

class DemographicController extends Controller
{
	protected $daterange;
	protected $start_date;
	protected $end_date;

	public function __construct(Request $request)
    {
    
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y/m/01 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y/m/d 23:59:59');
        
    }

	public function gender()
	{
		$noOfDays = round((strtotime($this->end_date) - strtotime($this->start_date)) / (60 * 60 * 24));

        $days = Days365::limit($noOfDays)->get();

        $male = 0;
        $female = 0;
        $none = 0;

        foreach ($days as $day) {
        	
        	$date = date('Y-m-d', strtotime("+".$day->day." days", strtotime($this->start_date)));
            
        	$day->date = $date;

        	$leads = Lead::join('patient_details as p', 'p.lead_id', '=', 'marketing_details.id')
                    ->join(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                        $join->on('p.id', '=', 'f.patient_id');
                    })
                    ->where(DB::raw("DATE_FORMAT(f.entry_date, '%Y-%m-%d')"), $date)
                    ->get();

            $day->male = $leads->where('gender', 'M')->count();
            $day->female = $leads->where('gender', 'F')->count();
            $day->none = $leads->count() - ($day->male + $day->female);

            $male += $day->male;
            $female += $day->female;
            $none += $day->none;
        }

        
    	$leads = Lead::join('patient_details as p', 'p.lead_id', '=', 'marketing_details.id')
                ->join(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                    $join->on('p.id', '=', 'f.patient_id');
                })
                ->where('end_date', '>=', date('Y-m-d'))
                ->get();

        $days->active_male = $leads->where('gender', 'M')->count();
        $days->active_female = $leads->where('gender', 'F')->count(); 
        $days->active_none = $leads->count() - ($days->active_male + $days->active_female);//;$leads->where('gender', '')->count();
        $days->active_total = $days->active_female + $days->active_male + $days->active_none;
        $days->male = $male;
        $days->female = $female;
        $days->none = $none;
        $days->total = $days->female + $days->male + $days->none;

        $data = array(
        	'menu'			=>	'reports',
        	'section'		=>	'demographics.gender',
        	'start_date'	=>	$this->start_date,
        	'end_date'		=>	$this->end_date,
        	'days'			=>	$days
        );

        return view('home')->with($data);
	}

    public function active()
    {
        $activePatients = DB::select("SELECT calDay as `date`, count(DISTINCT f.patient_id) as count FROM
                    (SELECT cast('".$this->start_date."' + interval `day` day as date) AS calDay FROM days365
                    WHERE cast('".$this->start_date."' + interval `day` day as date) <= '".$this->end_date."') AS calendar
                    LEFT JOIN fees_details as f ON (end_date >= calDay and f.start_date <= calDay)
                    group by calDay");

        $newPatients = DB::select("SELECT calDay as `date`, count(DISTINCT f.patient_id) as count FROM
                    (SELECT cast('".$this->start_date."' + interval `day` day as date) AS calDay FROM days365
                    WHERE cast('".$this->start_date."' + interval `day` day as date) <= '".$this->end_date."') AS calendar
                    LEFT JOIN fees_details as f ON (start_date = calDay)
                    group by calDay");

        $endPatients = DB::select("SELECT calDay as `date`, count(DISTINCT f.patient_id) as count FROM
                    (SELECT cast('".$this->start_date."' + interval `day` day as date) AS calDay FROM days365
                    WHERE cast('".$this->start_date."' + interval `day` day as date) <= '".$this->end_date."') AS calendar
                    LEFT JOIN fees_details as f ON (end_date = calDay)
                    group by calDay");
       
        /*$noOfDays = round((strtotime($this->end_date) - strtotime($this->start_date)) / (60 * 60 * 24));

        $days = Days365::limit($noOfDays)->get();

        foreach ($days as $day) {

            $date = date('Y-m-d', strtotime("+".$day->day." days", strtotime($this->start_date)));
            
            $day->date = $date;
            
            $patients = Patient::
                    join(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                        $join->on('patient_details.id', '=', 'f.patient_id');
                    })
                    ->where('end_date', '>=', $date)
                    ->where('start_date', '<', $date)
                    ->get();

            $day->active = $patients->count();
            $day->end = $patients->where('end_date', $date)->count();
            $day->start = Patient::
                        join(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                            $join->on('patient_details.id', '=', 'f.patient_id');
                        })
                        ->where('start_date', $date)
                        ->count();
        }*/

        
        $data = array(
            'menu'              =>  'reports',
            'section'           =>  'demographics.active',
            'start_date'        =>  $this->start_date,
            'end_date'          =>  $this->end_date,
            'activePatients'    =>  $activePatients,
            'endPatients'       =>  $endPatients,
            'newPatients'       =>  $newPatients
        );

        return view('home')->with($data);
    }
}