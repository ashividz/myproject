<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use App\Models\Lead;
use App\Models\User;
use App\Models\Patient;
use App\Models\LeadCre;
use App\Models\LeadSource;
use App\Models\LeadStatus;

class UpgradeController extends Controller
{
    
    public $daterange;
    public $start_date;
    public $end_date;

    public function __construct()
    {
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y/m/01 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y/m/d 23:59:59');        
    } 

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leads = Lead::with('cre')
                    ->with('patient', 'patient.fee')
                    ->leftJoin('lead_sources AS s', 'marketing_details.id', '=', 's.lead_id')
                    ->select('marketing_details.*', 's.sourced_by', 's.created_at AS sourced_date')
                    ->where('s.source_id', 22)
                    ->whereBetween('s.created_at', array($this->start_date, $this->end_date))
                    ->orderBy('s.created_at', 'DESC')
                    ->get();

        $summaries = DB::table('lead_sources AS s')
                    ->select('sourced_by', DB::RAW('COUNT(*) AS leads, COUNT(CASE WHEN f.entry_date >= s.created_at THEN f.entry_date END) AS conversions'))
                    ->join('patient_details AS p', 'p.lead_id', '=', 's.lead_id')

                    ->leftJoin(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id = B.patient_id)) AS f'), function($join) {
                        $join->on('p.id', '=', 'f.patient_id');
                    })
                    ->where('s.source_id', '22')
                    ->whereBetween('s.created_at', array($this->start_date, $this->end_date))
                    ->groupBy('sourced_by')
                    ->orderBy('conversions', 'DESC')
                    ->get();

        $data = array(
            'leads'         =>  $leads,
            'menu'          => 'reports',
            'section'       => 'upgrade',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'summaries'     =>  $summaries
        );

        return view('home')->with($data);  
    }

    public function viewLeads(Request $request)
    {
        $users = User::getUsersByRole('cre');
        
        $days  = $request->days ? $request->days : 10;
        
        $patients = Patient::getUpgradeList($days);
        
        $data = array(
            'menu'          =>  'marketing',
            'section'       =>  'upgrade',
            'days'          =>  $days,
            'users'         =>  $users,
            'patients'         =>  $patients
        );

        return view('home')->with($data);
    }

    public function saveLeads(Request $request)
    {
        $checks = $request->check;
        echo "<div style='text-align:left'>";
        foreach ($checks as $check) {
            $lead = Lead::find($check);
            
            LeadSource::saveSource($lead, $request);
            LeadStatus::saveStatus($lead, 1);
            LeadCre::saveCre($lead, $request->cre);

            echo "<b>" . $lead->name . "</b> : Allocated to <b>" . $request->cre . "</b><p>";

/*            if(!LeadCre::ifSameCre($lead, $request->cre)) //
            {
                LeadCre::saveCre($lead, $request);

                echo "<b>" . $lead->name . "</b> : Allocated to <b>" . $request->cre . "</b><p>";

            }
            else {
                echo  "<b>" . $lead->name . "</b> : Duplicate CRE <b>" . $request->cre . "</b><p>";
            }*/
        }
        echo "</div>";
    }
}
