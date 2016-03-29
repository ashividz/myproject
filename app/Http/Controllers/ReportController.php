<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Lead;
use App\Models\Fee;
use App\Models\Source;
use App\Models\LeadSource;
use App\Models\Patient;
use App\Models\User;
use App\Models\Days365;
use App\Models\Query;
use App\Models\Email;
use DB;
use Auth;
use DateTime;

class ReportController extends Controller
{
    protected $menu;
    protected $daterange;
    protected $start_date;
    protected $end_date;
    protected $user;

    public function __construct(Request $request) 
    {
        $this->menu = "reports";
        $this->user = isset($request->user) ? $request->user : Auth::user()->employee->name;
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
        $data = array(
            'menu'      => $this->menu,
            'section'       =>  'index'
        );

        return view('home')->with($data);
    }

    public function payments()
    {
        //Fee::clean();

        $fees = Fee::with('patient')
                ->with('source')
                ->whereBetween('entry_date', array($this->start_date, $this->end_date))
                ->get();

        $packages = Fee::select('valid_months AS name', DB::RAW('COUNT(*) AS count, SUM(total_amount) AS amount'))
                ->whereBetween('entry_date', array($this->start_date, $this->end_date))
                ->groupBy('valid_months')
                ->get();

        $data = array(
            'menu'          =>  $this->menu,
            'section'       =>  'payments',
            'fees'          =>  $fees,
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'i'             =>  '1',
            'packages'      =>  $packages
        );

        return view('home')->with($data);
    }

    public function channelConversion()
    {

        $sources = LeadSource::select('source_id', DB::RAW('count(*) AS leads'))
                    ->with('master')
                    ->whereBetween('created_at', array($this->start_date, $this->end_date))
                    ->groupBy('source_id')
                    ->get();


        $data = array(
            'menu'          => 'reports',
            'section'       => 'channel_conversion',
            'sources'       => $sources,
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date
        );

        return view('home')->with($data);

    }

    public function profiles() 
    {
        /*$ages = DB::SELECT("SELECT
            SUM(IF(age < 20,1,0)) as 'under20',
            SUM(IF(age BETWEEN 20 and 24,1,0)) as 'r2024',
            SUM(IF(age BETWEEN 25 and 29,1,0)) as 'r2529',
            SUM(IF(age BETWEEN 30 and 34,1,0)) as '30-34',
            SUM(IF(age BETWEEN 35 and 39,1,0)) as '35-39',
            SUM(IF(age BETWEEN 40 and 44,1,0)) as '40-44',
            SUM(IF(age BETWEEN 45 and 49,1,0)) as '45-49',
            SUM(IF(age BETWEEN 50 and 54,1,0)) as '50-54',
            SUM(IF(age BETWEEN 55 and 59,1,0)) as '55-59',
            SUM(IF(age BETWEEN 60 and 64,1,0)) as '60-64',
            SUM(IF(age BETWEEN 65 and 69,1,0)) as '65-69',
            SUM(IF(age BETWEEN 70 and 79,1,0)) as '70-79',
            SUM(IF(age >=80, 1, 0)) as 'over80',
            SUM(IF(age IS NULL, 1, 0)) as 'empty'
        FROM (SELECT id, TIMESTAMPDIFF(YEAR, dob, CURDATE()) AS age FROM marketing_details) as m
        JOIN patient_details p ON m.id = p.lead_id
        JOIN (SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f
        ON f.patient_id=p.id
        WHERE f.entry_date BETWEEN :start_date AND :end_date", 
        ['2015-11-01', $this->end_date]);*/

        /*$ages = Lead::select(DB::RAW("
                    SUM(IF(age < 20,1,0)) as 'under20',
                    SUM(IF(age BETWEEN 20 and 24,1,0)) as '20-24',
                    SUM(IF(age BETWEEN 25 and 29,1,0)) as '25-29',
                    SUM(IF(age BETWEEN 30 and 34,1,0)) as '30-34',
                    SUM(IF(age BETWEEN 35 and 39,1,0)) as '35-39',
                    SUM(IF(age BETWEEN 40 and 44,1,0)) as '40-44',
                    SUM(IF(age BETWEEN 45 and 49,1,0)) as '45-49',
                    SUM(IF(age BETWEEN 50 and 54,1,0)) as '50-54',
                    SUM(IF(age BETWEEN 55 and 59,1,0)) as '55-59',
                    SUM(IF(age BETWEEN 60 and 64,1,0)) as '60-64',
                    SUM(IF(age BETWEEN 65 and 69,1,0)) as '65-69',
                    SUM(IF(age BETWEEN 70 and 79,1,0)) as '70-79',
                    SUM(IF(age >=80, 1, 0)) as 'over80',
                    SUM(IF(age IS NULL, 1, 0)) as 'empty'
                FROM (SELECT id, TIMESTAMPDIFF(YEAR, dob, CURDATE()) AS age"))
                ->join('patient_details AS p', 'marketing_details.id', '=', 'p.lead_id')

                ->leftJoin(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                        $join->on('p.id', '=', 'f.patient_id');
                    })
                ->whereBetween('f.entry_date', array('2015-11-01', $this->end_date))
                ->first();*/
        //dd($ages);

        $data = array(
            'menu'      =>  $this->menu,
            'section'   =>  'age'
        );

        return view('home')->with($data);
    }

    //JSON customers age. Not finished yet
    public function getAge()
    {
        $patients = Patient::select('patient_details.*')
                ->with('lead', 'fee')
                ->join('marketing_details as m', 'patient_details.lead_id', '=', 'm.id')
                ->join(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                    $join->on('patient_details.id', '=', 'f.patient_id');
                })
                ->whereBetween('start_date', array($this->start_date, $this->end_date))
                //->groupBy('age')
                //->whereNotNull('age')
                ->orderBy('age')
                //->limit(50)
                ->get();
        //dd($patients);
        $activePatients = Patient::select('patient_details.*')
                ->with('lead', 'fee')
                ->join('marketing_details as m', 'patient_details.lead_id', '=', 'm.id')
                ->join(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                    $join->on('patient_details.id', '=', 'f.patient_id');
                })
                ->where('end_date', '>=', date('Y-m-d'))
                ->orderBy('age')
                ->get();

        $data = array(
            'menu'          =>  $this->menu,
            'section'       =>  'patients.ages',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'patients'      =>  $patients,
            'activePatients'=>  $activePatients,
            'i'             =>  1,
            'j'             =>  1      
        );

        return view('home')->with($data);

    }

    //Country Summary
    public function getCountryWisePatientSummary(Request $request)
    {
        $end_date = isset($_REQUEST['date']) ? date("Y-m-d h:i:s", strtotime($_REQUEST['date'])) : date("Y-m-d h:i:s");

        $countries = Patient::join('marketing_details AS m', 'm.id', '=', 'patient_details.lead_id')
                        ->join(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                                $join->on('patient_details.id', '=', 'f.patient_id');
                            })
                        ->leftJoin('yuwow_alpha_1_0.countries AS c', 'c.country_code', '=', 'm.country')
                        ->where('f.end_date', '>=', $end_date)
                        ->where('f.start_date', '<=', $end_date)
                        ->groupBy('country_name')
                        ->orderBy('count', 'desc')
                        ->select(DB::RAW('c.country_name, COUNT(*) AS count'))
                        ->get();

        $regions = Patient::join('marketing_details AS m', 'm.id', '=', 'patient_details.lead_id')
                        ->join(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                                $join->on('patient_details.id', '=', 'f.patient_id');
                            })
                        ->leftJoin('yuwow_alpha_1_0.region_codes AS r', 'r.region_code', '=', 'm.state')
                        ->leftJoin('yuwow_alpha_1_0.countries AS c', 'c.country_code', '=', 'm.country')
                        ->where('f.end_date', '>=', $end_date)
                        ->where('f.start_date', '<=', $end_date)
                        ->groupBy(['region_name', 'country_name'])
                        ->orderBy('count', 'desc')
                        ->select(DB::RAW('region_name, country_name, COUNT(*) AS count'))
                        ->get();

        $cities = Patient::join('marketing_details AS m', 'm.id', '=', 'patient_details.lead_id')
                        ->join(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                                $join->on('patient_details.id', '=', 'f.patient_id');
                            })
                        ->leftJoin('yuwow_alpha_1_0.countries AS c', 'c.country_code', '=', 'm.country')
                        ->leftJoin('yuwow_alpha_1_0.region_codes AS r', 'r.region_code', '=', 'm.state')
                        ->where('f.end_date', '>=', $end_date)
                        ->where('f.start_date', '<=', $end_date)
                        ->groupBy(['city', 'region_name', 'country_name'])
                        ->orderBy('count', 'desc')
                        ->select(DB::RAW('city, region_name, country_name, COUNT(*) AS count'))
                        ->get();

                        
        
        $data = array(
            'menu'          =>  $this->menu,
            'section'       =>  'patients.country',
            'end_date'      =>  $end_date,
            'i'             =>  '1',
            'countries'     =>  $countries,
            'regions'       =>  $regions,
            'cities'        =>  $cities        
        );

        return view('home')->with($data);

    }

    //New Patients Country Wise
    public function getNewPatients(Request $request)
    {
        $countries = Patient::join('marketing_details AS m', 'm.id', '=', 'patient_details.lead_id')
                        ->join(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                                $join->on('patient_details.id', '=', 'f.patient_id');
                            })
                        ->leftJoin('yuwow_alpha_1_0.countries AS c', 'c.country_code', '=', 'm.country')
                        ->whereBetween('end_date', array($this->start_date, $this->end_date))
                        ->groupBy('country_name')
                        ->orderBy('count', 'desc')
                        ->select(DB::RAW('c.country_name, COUNT(*) AS count'))
                        ->get();

        $regions = Patient::join('marketing_details AS m', 'm.id', '=', 'patient_details.lead_id')
                        ->join(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                                $join->on('patient_details.id', '=', 'f.patient_id');
                            })
                        ->leftJoin('yuwow_alpha_1_0.region_codes AS r', 'r.region_code', '=', 'm.state')
                        ->whereBetween('end_date', array($this->start_date, $this->end_date))
                        ->groupBy('region_name')
                        ->orderBy('count', 'desc')
                        ->select(DB::RAW('region_name, COUNT(*) AS count'))
                        ->get();

        $cities = Patient::join('marketing_details AS m', 'm.id', '=', 'patient_details.lead_id')
                        ->join(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                                $join->on('patient_details.id', '=', 'f.patient_id');
                            })
                        ->whereBetween('end_date', array($this->start_date, $this->end_date))
                        ->groupBy('city')
                        ->orderBy('count', 'desc')
                        ->select(DB::RAW('city, COUNT(*) AS count'))
                        ->get();

                        
        
        $data = array(
            'menu'          =>  $this->menu,
            'section'       =>  'patients.new',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'countries'     =>  $countries,
            'regions'       =>  $regions,
            'cities'        =>  $cities        
        );

        return view('home')->with($data);

    }

    /*
       Daily Source Perfomrmance 
    */

    public function dailyPerformance()
    {
        $noOfDays = (strtotime($this->end_date) - strtotime($this->start_date)) / (60 * 60 * 24) + 1;

        $days = Days365::limit($noOfDays)->get();

        $sources = Source::get();
        
        foreach ($days as $day) {
            $date_start = date('Y-m-d', strtotime("+".$day->day." days", strtotime($this->start_date)));
            $date_end = date('Y-m-d 23:59:59', strtotime($date_start));
            //echo $date_start. " " .$date_end. '<p>';

            $day->date = $date_start;
            
            $day->leads = Lead::select(DB::RAW('source_id as id, COUNT(*) AS count'))                        
                        ->whereBetween('created_at', array($date_start, $date_end))
                        ->groupBy('source_id')
                        ->get(); 

            $day->sources = LeadSource::select(DB::RAW('source_id as id, COUNT(*) AS count'))
                        ->whereBetween('created_at', array($date_start, $date_end))
                        ->groupBy('source_id')
                        ->get(); 

            $day->queries = Query::select(DB::RAW('s.id as id, COUNT(*) AS count'))
                            ->leftJoin('m_lead_source as s', 's.source', '=', 'queries.source')
                            ->whereBetween('date', array($date_start, $date_end))
                            ->groupBy('s.source')
                            ->get();                       
        }

        $days->leads = Lead::select(DB::RAW('source_id as id, COUNT(*) AS count'))                        
                    ->whereBetween('created_at', array($this->start_date, $this->end_date))
                    ->groupBy('source_id')
                    ->get(); 

        $days->sources = LeadSource::select(DB::RAW('source_id as id, COUNT(*) AS count'))
                    ->whereBetween('created_at', array($this->start_date, $this->end_date))
                    ->groupBy('source_id')
                    ->get(); 

        $days->queries = Query::select(DB::RAW('s.id as id, COUNT(*) AS count'))
                        ->leftJoin('m_lead_source as s', 's.source', '=', 'queries.source')
                        ->whereBetween('date', array($this->start_date, $this->end_date))
                        ->groupBy('s.source')
                        ->get();
       
//dd($days);
        

        $data = array(
            'menu'          =>  $this->menu,
            'section'       => 'leads.performance',
            'sources'       =>  $sources,
            'days'          =>  $days,
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date
        );

        return view('home')->with($data);
    }

    /*
       CRE Perfomrmance Source Wise
    */

    public function creWiseSourcePerformance()
    {
        $users = User::getUsersByRole('cre');
        
        $sources = Source::get();

        foreach ($sources as $source) {
            
            $source->leads = Lead::leftJoin(DB::raw('(SELECT * FROM lead_cre A WHERE id = (SELECT MAX(id) FROM lead_cre B WHERE A.lead_id=B.lead_id)) AS c'), function($join) {
                    $join->on('marketing_details.id', '=', 'c.lead_id');
                })
                ->where('cre', $this->user)
                ->where('source_id', $source->id)
                ->whereBetween('c.created_at', array($this->start_date, $this->end_date))
                ->count();

            $patients = Patient::join('fees_details AS f', 'f.patient_id', '=', 'patient_details.id') 
                        ->where('cre', $this->user)
                        ->where('source_id', $source->id) 
                        ->whereBetween('f.entry_date', array($this->start_date, $this->end_date))
                        ->get();

            //echo $source->source_name . " : ".$patients->count() . " - Rs.  ".$patients->sum('total_amount') . "<p>";

            $source->patients = $patients->count();
            $source->amount = $patients->sum('total_amount');
        }

        //dd($sources);

        $data = array(
            'menu'          =>  $this->menu,
            'section'       => 'cre.channel',
            'users'         =>  $users,
            'name'          =>  $this->user,
            'sources'       =>  $sources,
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date
        );

        return view('home')->with($data);
    }

    public function emails()
    {
        $emails = Email::whereBetween('created_at', array($this->start_date, $this->end_date))
                    ->limit(env('DB_LIMIT'))
                    ->get();

        $summaries = Email::select(DB::RAW('e.name, count(*) as count'))
                    ->join('users AS u', 'u.id', '=', 'emails.user_id')
                    ->join('employees AS e', 'e.id', '=', 'u.emp_id')
                    ->whereBetween('emails.created_at', array($this->start_date, $this->end_date))
                    ->groupBy('user_id')
                    ->get();

        $data = array(
            'menu'          =>  $this->menu,
            'section'       => 'emails',
            'emails'        =>  $emails,
            'summaries'     =>  $summaries,
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'i'             =>  '1'
        );

        return view('home')->with($data);
    }

    public function occupation()
    {
        $patients = Patient::with('lead', 'tags')
                        ->whereHas('fee', function($q){
                            $q->whereBetween('created_at', array($this->start_date, $this->end_date));
                        } )
                        ->get();
        //dd($patients);
        $data = array(
            'menu'          =>  'reports',
            'section'       => 'patients.occupation',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'patients'      =>  $patients,
            'i'             =>  1
        );

        return view('home')->with($data);
                       
    }
    
}
