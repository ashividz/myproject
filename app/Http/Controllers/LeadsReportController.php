<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Leads;
use App\Models\Lead;
use App\Models\Patient;
use App\Models\Fee;
use App\Models\Diet;
use App\Models\LeadSource;
use App\Models\LeadCre;
use App\Models\LeadStatus;
use App\Models\CallDisposition;
use App\Models\OBD;
use App\Models\Cod;
use DB;
use Auth;
use App\Support\Helper;
use Redirect;

class LeadsReportController extends Controller
{
    protected $daterange;
    protected $start_date;
    protected $end_date;

    public function __construct()
    {        
        setlocale(LC_MONETARY,"en_IN");
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y/m/d 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y/m/d 23:59:59');
    }

    //Channel Conversion Performance Report
    public function viewChannelConversion()
    {
        $leads = DB::table('marketing_details AS m')
                ->select('s.source', 'mls.source_name', DB::RAW('COUNT(*) AS leads'))
                ->leftJoin(DB::raw('(SELECT * FROM lead_sources A WHERE id = (SELECT MAX(id) FROM lead_sources B WHERE A.lead_id=B.lead_id)) AS s'), function($join) {
                        $join->on('m.id', '=', 's.lead_id');
                    })
                /*->leftJoin('patient_details AS p', 'p.lead_id', '=', 'm.id')
                ->leftJoin(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                        $join->on('f.patient_id', '=', 'p.id');
                    })*/
                ->leftJoin('m_lead_source AS mls', 'mls.id', '=', 's.source')
                ->whereBetween('s.created_at', array($this->start_date, $this->end_date))
                ->groupBy('s.source')
                ->orderBy('leads', 'DESC')
                ->get();


       $data = array(
            'menu'          => 'lead',
            'section'       => 'reports.channel_conversion',
            'leads'         => $leads,
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date
        );

        return view('home')->with($data);

    }
}