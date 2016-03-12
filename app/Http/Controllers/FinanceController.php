<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Patient;
use App\Models\Fee;
use App\Models\Source;
use Auth;
use DB;

class FinanceController extends Controller
{
    protected $menu;
    protected $daterange;
    protected $start_date;
    protected $end_date;

    public function __construct() 
    {
    	$this->menu = "finance";
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y/m/01 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y/m/d 23:59:59');
    }

    public function index() {

    	$data = array(
            "menu"      => $this->menu,
            "section"   => "dashboard"
        );

        return view('home')->with($data);
    }

    public function searchPatient(Request $request) {

    	$patients = Patient::with('lead')
                    ->where('id', $request->patient)
    				->orWhere('lead_id', $request->patient)
    				->orWhere('enquiry_no', $request->patient)
    				->orWhere('registration_no', $request->patient)
    				->get();

    	$data = array(
            "menu"      => "finance",
            "section"   => "break",
            'patients' 	=> $patients,
            'search'	=> $request->patient
        );

        return view('home')->with($data);
    }

    public function viewBreakAdjust($id) {

    	//$patients = array();

    	$patient = Patient::with('lead')
    				->with('fees')
    				->find($id);

    	$data = array(
            "menu"      => "finance",
            "section"   => "break",
            //'patients'	=>	$patients,
            'patient' 	=> 	$patient
        );

        return view('home')->with($data);
    }

    public function saveBreakAdjust(Request $request)
    {
    	$fee = Fee::find($request->id);
    	$fee->end_date = $request->end_date;
    	$fee->remark = $request->remark . " - " . Auth::user()->employee->name;
    	$fee->save();

    	return "Break Adjusted";
    }

    public function viewPayments()
    {
        Fee::clean();

        $fees = Fee::with('patient')
                ->with('source')
                ->whereBetween('entry_date', array($this->start_date, $this->end_date))
                ->get();

        //Update Status
        foreach ($fees as $fee) 
        {
            if($fee->created_at > "2015-09-01")
            {
                $lead = $fee->patient->lead;
                if($lead->status_id <> 1)
                {
                    $lead->status_id = 5;
                    $lead->save();
                }
            }
        }

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

    public function saveCre(Request $request)
    {
        $fee = Fee::find($request->id);

        $fee->cre = $request->value;
        $fee->save();
        
        return $request->value;
    }

    public function saveSource(Request $request)
    {
        $fee = Fee::find($request->id);

        $fee->source_id = $request->value;
        $fee->save();

        $source = Source::find($request->value);

        return $source->source_name;
    }

    public function saveAudit(Request $request)
    {
        $fee = Fee::find($request->id);

        $fee->audit = $request->value;
        $fee->audited_by = Auth::user()->employee->name;
        $fee->audited_at = date("Y-m-d h:i:s");
        $fee->save();

        return $request->value == 1 ? "Correct" : "Incorrect";
    }
}
