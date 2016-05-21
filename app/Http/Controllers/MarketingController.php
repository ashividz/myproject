<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use App\Support\Helper;

use App\Models\Query;
use App\Models\Source;
use App\Models\Country;
use App\Models\User;
use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\LeadCre;
use App\Models\LeadStatus;
use App\Models\Patient;
use App\Models\DialerPush;
use App\Models\Status;
use App\Models\LeadProgram;

use DB;
use Auth;
use App\DND;

class MarketingController extends Controller
{
    protected $menu;
    protected $daterange;
    protected $start_date;
    protected $end_date;
    protected $user;

    public function __construct(Request $request)
    {
		$this->menu = 'marketing';

        $this->user = isset($request->user) ? $request->user : Auth::user()->employee->name;
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y/m/d 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y/m/d 23:59:59');
 
    }

    public function index()
    {
    	$data = array(
    		'menu'		=>	$this->menu,
    		'section'	=>	'dashboard'
    	);

    	return view('home')->with($data);
    }

    public function viewLeads()
    {
        $leads = Lead::getLeads($this->start_date, $this->end_date);        

        $statuses = Status::get();

        $data = array(
            'menu'          =>  $this->menu,
            'section'       =>  'leads',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'statuses'      =>  $statuses,
            'leads'         =>  $leads,
            'i'             =>  '1'
        );

        return view('home')->with($data);
    }

    public function viewLeadDistribution()
    {
    	//dd(Query::getLastQueryId(2));
        $website = $this->fetchQueries(1);
        $nlp = $this->fetchQueries(2);

    	$queries = Query::whereBetween('date', array($this->start_date, $this->end_date))
                    ->whereNull('shs')
                    //->where('status', 0)
                    ->with('lead' ,'lead.source', 'lead.cre')
    				->get();

        $sources = Source::get();

        $countries = Country::get();

        $users = User::getUsersByRole('cre');

    	$data = array(
    		'menu'		    =>	$this->menu,
    		'section'	    =>	'lead_distribution',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
    		'queries'	    =>	$queries,
            'sources'       =>  $sources,
            'countries'     =>  $countries,
            'users'         =>  $users,
            'website'       =>  $website,
            'nlp'           =>  $nlp
    	);

    	return view('home')->with($data);
    }

     public function viewSHSLeadDistribution()
    {
        //dd(Query::getLastQueryId(2));
        $website = $this->fetchQueries(1);
        $nlp = $this->fetchQueries(2);

        $queries = Query::whereBetween('date', array($this->start_date, $this->end_date))
                    ->where('shs', '1')
                    //->where('status', 0)
                    ->with('lead' ,'lead.source', 'lead.cre')
                    ->get();

        $sources = Source::get();

        $countries = Country::get();

        $users = User::getUsersByRole('cre');

        $data = array(
            'menu'          =>  $this->menu,
            'section'       =>  'lead_distribution_sha',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'queries'       =>  $queries,
            'sources'       =>  $sources,
            'countries'     =>  $countries,
            'users'         =>  $users,
            'website'       =>  $website,
            'nlp'           =>  $nlp
        );

        return view('home')->with($data);
    }

    private function fetchQueries($vendor)
    {
        $count = 0;

        try {
            $query_id = Query::getLastQueryId($vendor);

            $client = new \GuzzleHttp\Client(['base_uri' => 'http://website']);
            $response = $client->request('GET','/query/' . $vendor . '/' . $query_id);
            //dd($response);
            $queries = $response->getBody()->getContents();
            $queries = json_decode($queries);
            
            foreach ($queries as $query) {
                if(Query::saveQuery($vendor, $query))
                {
                    $count++;
                }
            }

            return $count;

        } catch (RequestException $e) {
            var_dump($e->getResponse()->getBody()->getContent());
        }
        
    }

    public function saveLeadDistribution(Request $request)
    {
        $size = count($request->cre);

        for ($i=1; $i <= $size; $i++) { 

            //Skip if no CRE
            if($request->cre[$i] == "")
            {    

                //dd($request->cre[$i]);          
                continue;
            }
            //dd($request);   
            //Check Duplicate Lead
            $lead = Lead::where('phone', Helper::properMobile($request->phone[$i]))->first();

            if (!isset($lead)) {
                $lead = Lead::where('email', $request->email[$i])->first();
            }            

            if(isset($lead)) //Lead already exists.
            {
                //Check if Source Exists

                if(!LeadSource::ifSameSource($lead, $request->source[$i]))
                {
                    LeadSource::saveSource($lead, $request, $i); //Add lead source if source does not exist.
                }
                
                //Update Query Status as Lead Already Exists

                Query::updateQuery($request->id[$i], $lead, 2); //

                continue;
            }

            //Save Lead
            $lead = Lead::saveLead($request, $i);
            $this->check($lead);
            //Save Cre
            LeadCre::saveCre($lead, $request->cre[$i]);

            //Save Source
            LeadSource::saveSource($lead, $request, $i);

            //Save Status
            LeadStatus::saveStatus($lead, 1);

            //Update Query Status as Lead Saved
            //Query::updateQuery($request->id[$i], $lead, 1);
        }


        return $this->viewLeadDistribution();
    }

    public function saveSHSLeadDistribution(Request $request)
    {
      $size = count($request->cre);

        for ($i=1; $i <= $size; $i++) { 

            //Skip if no CRE
            if($request->cre[$i] == "")
            {    

                //dd($request->cre[$i]);          
                continue;
            }
            //dd($request);   
            //Check Duplicate Lead
            $lead = Lead::where('phone', Helper::properMobile($request->phone[$i]))->first();

            if (!isset($lead)) {
                $lead = Lead::where('email', $request->email[$i])->first();
            }            

            if(isset($lead)) //Lead already exists.
            {
                //Check if Source Exists

                if(!LeadSource::ifSameSource($lead, $request->source[$i]))
                {
                    LeadSource::saveSource($lead, $request, $i); //Add lead source if source does not exist.
                }
                
                //Update Query Status as Lead Already Exists

                Query::updateQuery($request->id[$i], $lead, 2); //

                continue;
            }

            //Save Lead
            $lead = Lead::saveLead($request, $i);

            
            $lead_program = new LeadProgram;
            $lead_program->lead_id = $lead->id;
            $lead_program->program_id = "10";
            $lead_program->save();

            $this->check($lead);
            //Save Cre
            LeadCre::saveCre($lead, $request->cre[$i]);

            //Save Source
            LeadSource::saveSource($lead, $request, $i);

            //Save Status
            LeadStatus::saveStatus($lead, 1);

            //Update Query Status as Lead Saved
            //Query::updateQuery($request->id[$i], $lead, 1);
        }


        return $this->viewSHSLeadDistribution();
    }

    public function viewAddLead()
    {
        $data = array(
            'menu'      => 'marketing',
            'section'   => 'add_lead'
        );

        return view('home')->with($data);
    }

    public function saveLead(Request $request)
    {
        if (Lead::isExistingMobile($request->mobile)) {
            return "Duplicate Mobile";
        }
        if (trim($request->email) <> "" && Lead::isExistingEmail($request->email)) {
            return "Duplicate Email ";
        }

        $lead = Lead::addLead($request);
        $this->check($lead);
        return redirect("/lead/" . $lead->id . "/viewDispositions");
    }

    public function check($lead)
    {
        $dnd = new DND;

        if($dnd->scrub($lead->phone) == true){

            echo '<p>Phone : '.$lead->id.$lead->name;
            Lead::setPhoneDNDStatus($lead, 1);

        } elseif ($dnd->scrub($lead->phone) == false) {
            Lead::setPhoneDNDStatus($lead, 0);
        }


        
        
        if($dnd->scrub($lead->mobile) == true){

            Lead::setMobileDNDStatus($lead, 1);
            echo '<p>Mobile : '.$lead->id.$lead->name;

        } elseif ($dnd->scrub($lead->mobile) == false) {

            Lead::setMobileDNDStatus($lead, 0);
        }
    }

    public function search(Request $request) 
    {
        $leads = array();
        $searchFor = "";
        $pin = $request->pin;
        
        if(isset($pin)) {
            $searchFor = "<b>PIN :</b> " . $pin;
            $leads = Lead::with('patient')
                    ->where('zip', $pin)
                    ->get();
        }
        
        $data = array(
            'menu'      => $this->menu,
            'section'   => 'search',
            'leads'     =>  $leads,
            'searchFor' =>  $searchFor
        );

        return view('home')->with($data);
    }

    public function churnLeads(Request $request)
    {
        $users = User::getUsersByRole('cre');
        $cre  = $request->user;

        $leads = Lead::getLeadsByUser($cre, $this->start_date, $this->end_date);

        $data = array(
            'menu'          =>  $this->menu,
            'section'       =>  'churn',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'users'         =>  $users,
            'name'           =>  $cre,
            'leads'         =>  $leads
        );

        return view('home')->with($data);
    }

    public function saveChurnLeads(Request $request)
    {
        $checks = $request->check;
        
        echo "<div style='text-align:left'>";
        
        foreach ($checks as $check) {
            
            $lead = Lead::find($check);
            
            if(!LeadCre::ifSameCre($lead, $request->cre))
            {
                LeadCre::saveCre($lead, $request->cre);
                LeadStatus::saveStatus($lead, 1);
                echo "<b>" . $lead->name . "</b>: CRE Added <b>" . $request->cre . "</b></p>";
            }
            else {
                echo "<b>" . $lead->name . "</b>: CRE already exists <b>" . $request->cre . "</b></p>";
            }

        }
    }

    public function viewProgramEnd()
    {
        $users = User::getUsersByRole('cre');

        $patients = Patient::getProgramEnd($this->start_date, $this->end_date);

        $data = array(
            'menu'              =>  $this->menu,
            'section'           =>  'program_end',
            'users'             =>  $users,
            'patients'          =>  $patients,
            'start_date'        =>  $this->start_date,
            'end_date'          =>  $this->end_date,
            'i'                 =>  '1'
        );

        return view('home')->with($data);
    }

    public function saveRejoin(Request $request)
    {
        if (!$request->check || !$request->cre) {
           return "Error : Select User or Leads";
        }
        $checks = $request->check;
        
        foreach ($checks as $check) {
            
            $lead = Lead::find($check);

            if(date('Y/m/d', strtotime($request->end_date[$check])) > date('Y/m/d'))
            {
                echo "<b>" . $lead->name . "</b>: Program not finished yet<p>";
                continue;
            }

            if(LeadCre::ifMultipleCreOnSameDate($lead))
            {
                echo "<b>" . $lead->name . "</b>: Unable to add Multiple CREs on same day<p>";
                continue;
            }
            
            if(!LeadCre::ifSameCre($lead, $request->cre))
            {
                LeadCre::saveCre($lead, $request->cre);
                echo "<b>" . $lead->name . "</b>: <b>CRE</b> :  Yes";
            }
            else {
                echo "<b>" . $lead->name . "</b>: <b>CRE</b> : Duplicate ";
            }

            //if(!LeadSource::ifSameSource($lead, 23))
            {
                LeadSource::saveSource($lead, $request);
                echo " <b>Source</b> : Added<p>";
            }
            /*else {
                echo " <b>Source</b> :Duplicate<p>";
            }*/

            if(!LeadStatus::ifSameStatus($lead, 1))
            {
                LeadStatus::saveStatus($lead, 1);
            }
        }
    }

    public function viewChannelPerformance()
    {
        $channels = array();

        $date = strtotime($this->start_date);

        $sources = Source::get();
        
        $days = floor((strtotime($this->end_date) - strtotime($this->start_date))/(60*60*24));

        for ($i=0; $i <= $days; $i++) { 

            $channel = array();

            $channel['date'] = $date;

            foreach ($sources as $source) {
                $count = Lead::getChannelPerformanceBySource($source->id, date('Y/m/d 0:0:0', $date), date('Y/m/d 23:59:59', $date));
                
                $cnt[$source->id] = $count->cnt;
            }

            $channel = array_add($channel, 'counts', $cnt);
                
            array_push($channels, $channel);
            
            $date = strtotime("+1 day", $date); 
        }

        //dd($channels);

        $data = array(
            'menu'              =>  $this->menu,
            'section'           =>  'channel_performance',
            'start_date'        =>  $this->start_date,
            'end_date'          =>  $this->end_date,
            'sources'           =>  $sources,
            'channels'          =>  json_encode($channels)
        );

        return view('home')->with($data);
    }

    public function viewChannelDistribution()
    {
        $channels = array();

        $date = strtotime($this->start_date);

        $sources = Source::get();
        
        $days = floor((strtotime($this->end_date) - strtotime($this->start_date))/(60*60*24));

        for ($i=0; $i <= $days; $i++) { 

            $channel = array();
            $total = 0;        

            $channel['date'] = $date;

            foreach ($sources as $source) {
                $count = LeadSource::getChannelDistributionBySource($source->id, date('Y/m/d 0:0:0', $date), date('Y/m/d 23:59:59', $date));
                
                $cnt[$source->id] = $count->cnt;
                $total += $count->cnt;
            }

            $channel = array_add($channel, 'counts', $cnt);

            $channel = array_add($channel, 'total', $total);   

            array_push($channels, $channel);
            
            $date = strtotime("+1 day", $date); 
        }

        //dd($channels);

        $data = array(
            'menu'              =>  $this->menu,
            'section'           =>  'channel_distribution',
            'start_date'        =>  $this->start_date,
            'end_date'          =>  $this->end_date,
            'sources'           =>  $sources,
            'channels'          =>  json_encode($channels)
        );

        return view('home')->with($data);
    }

    public function uploadLead()
    {
        $data = array(
            'menu'              =>  $this->menu,
            'section'           =>  'upload'
        );

        return view('home')->with($data);
    }

    //Display Leads without CRE assigned
    public function noCre()
    {
        $leads = Lead::select('marketing_details.*')
                ->with('disposition','source')
                ->leftJoin('lead_cre AS c', 'marketing_details.id', '=', 'c.lead_id')
                ->whereBetween('marketing_details.created_at', array($this->start_date, $this->end_date))
                ->whereNull('c.id')
                ->limit(env('DB_LIMIT'))
                ->get();


        $data = array(
            'menu'              =>  $this->menu,
            'section'           =>  'no_cre',
            'start_date'        =>  $this->start_date,
            'end_date'          =>  $this->end_date,
            'leads'             =>  $leads,
            'i'                 =>  '1'
        );

        return view('home')->with($data);
    }

    public function duplicateEmail()
    {
        $leads = Lead::with('patient')
                ->join(DB::raw("(SELECT email FROM marketing_details WHERE email IS NOT NULL GROUP BY email HAVING COUNT( id ) > 1) AS m2"), function($join) {
                        $join->on('marketing_details.email', '=', 'm2.email');
                    })
                ->whereNotNull("m2.email")
                ->orWhere("m2.email", '<>', '')
                ->limit(env('DB_LIMIT'))
                ->get();
        //DB::SELECT("SELECT m1.id, m1.name, m1.email,  m1.phone, m1.mobile FROM marketing_details m1 INNER JOIN (SELECT email FROM marketing_details WHERE email IS NOT NULL GROUP BY email HAVING COUNT( id ) > 2 ) m2 ON m2.email=m1.email WHERE m1.email IS NOT NULL");
        
        $data = array(
            'menu'      => $this->menu,
            'section'   => 'duplicate_email',
            'leads'      => $leads
            );

        return view('home')->with($data);
    }

    public function duplicatePhone()
    {
        $leads = Lead::with('patient')
                ->join(DB::raw("(SELECT phone, created_at FROM marketing_details WHERE phone IS NOT NULL GROUP BY phone HAVING COUNT( id ) > 1) AS m2"), function($join) {
                        $join->on('marketing_details.phone', '=', 'm2.phone');
                    })
                ->whereHas('patient.fees', function($q) {
                    $q->where('entry_date', '>=', '2015-04-01');
                })
                ->whereNotNull("m2.phone")
                ->orWhere(trim('m2.phone'), '<>', '')
                ->limit(env('DB_LIMIT'))
                ->orderBy('m2.created_at', 'DESC')
                ->get();
        //DB::SELECT("SELECT m1.id, m1.name, m1.email,  m1.phone, m1.mobile FROM marketing_details m1 INNER JOIN (SELECT email FROM marketing_details WHERE email IS NOT NULL GROUP BY email HAVING COUNT( id ) > 2 ) m2 ON m2.email=m1.email WHERE m1.email IS NOT NULL");
        
        $data = array(
            'menu'      => $this->menu,
            'section'   => 'duplicate_phone',
            'leads'      => $leads
            );

        return view('home')->with($data);
    }

    //Leads without Phone and Mobile
    public function noContact()
    {
        DB::update("UPDATE marketing_details SET phone = NULL WHERE phone = '' ");
        DB::update("UPDATE marketing_details SET mobile = NULL WHERE mobile = '' ");
        
        $leads = Lead::whereNull('phone')
                        ->whereNull('mobile')
                        ->get();
        
        $data = array(
            'menu'      => $this->menu,
            'section'   => 'no_contact',
            'leads'      => $leads,
            'i'         =>  '1'
            );

        return view('home')->with($data);
    }
    /*
        Conversion Report from Dialer Push
    */
    public function viewDialerPush()
    {
        $calls = DialerPush::with('lead')
                        ->whereBetween('created_at', array($this->start_date, $this->end_date))
                        ->get();
        
        $data = array(
            'menu'          => $this->menu,
            'section'       => 'reports.dialer_push',
            'calls'         => $calls,
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'i'             =>  '1'
            );

        return view('home')->with($data);
    }

    /*
       Lead Distribution
    */
    public function leadDistribution()
    {
        $x = array();
        $start_date = strtotime("2015-12-01");
        $end_date = strtotime("2015-12-22");
        $days = ($end_date - $start_date)/(60*60*24);

        $leads = Lead::whereBetween('created_at', array($start_date, $end_date))
                    ->get();

        for ($i=0; $i < $days; $i++) { 
            $date_start = date('Y-m-d', strtotime('+ '.$i.' days', $start_date));
            $date_end = date('Y-m-d 23:59:59', strtotime('+ '.$i.' days', $start_date));
            $lead = Lead::whereBetween('created_at', array($date_start, $date_end))->select(DB::raw("count(*) AS count, source_id"))->groupBy('source_id')->get();
            $lead->date = $date_start;
            $x[$i] = $lead;
        }
         
        dd($x);
    }

    public function deadLeads()
    {
        $cre = $this->user;
        
        $leads = Lead::SELECT('marketing_details.*')
                    ->with(['disposition'=> function($q) use ($cre) {
                            $q->where('name', $cre);
                        }])
                    ->leftJoin(DB::raw('(SELECT * FROM lead_cre A WHERE id = (SELECT MAX(id) FROM lead_cre B WHERE A.lead_id=B.lead_id)) AS c'), function($join) {
                            $join->on('marketing_details.id', '=', 'c.lead_id');
                        })
                    ->whereBetween('c.created_at', array($this->start_date, $this->end_date))
                    ->where('c.cre', $cre)
                    ->where('status_id', '6')
                    ->orderBy('c.id', 'DESC')
                    ->limit(env('DB_LIMIT'))
                    ->get();

        
        $users = User::getUsersByRole('cre');

        $data = array(
            'menu'          => $this->menu,
            'section'       => 'dead',
            'start_date'    => $this->start_date,
            'end_date'      => $this->end_date,
            'leads'         => $leads,
            'users'         => $users,
            'name'           => $cre
        );

        return view('home')->with($data);
    }

    public function churnDeadLeads(Request $request)
    {
        $checks = $request->check;

        if($checks && $request->cre <> '') {
            $msg = '';
            
            $msg .= "<div style='text-align:left'>";
            
            foreach ($checks as $check) {
                
                $lead = Lead::find($check);

                LeadCre::saveCre($lead, $request->cre);
                LeadStatus::saveStatus($lead, 1);
                $msg .=  "<b>" . $lead->name . "</b>: CRE Added <b>" . $request->cre . "</b></p>";
                
            }
            $msg .= "</div>";

            return $msg;
        }

        return "Error : No Leads or CRE Selected";   
    }
}
