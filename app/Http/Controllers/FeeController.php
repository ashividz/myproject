<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\FeeRequest;

use App\Models\Patient;
use App\Models\Fee;
use App\Models\User;
use App\Models\Source;
use App\Models\LeadStatus;
use Auth;
use DB;

class FeeController extends Controller
{
    protected $daterange;
    protected $start_date;
    protected $end_date;

    public function __construct()
    {
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y/m/d 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y/m/d 23:59:59');
    }

    public function show($id)
    {
    	$patient = Patient::find($id);

		$users = User::getUsersByRole('cre');

		$sources = Source::get();

		$data = array(			
            'menu'              =>  'patient',
            'section'           =>  'fee',
            'patient'           =>  $patient,
            'users'				=>	$users,
            'sources'			=>	$sources
        );
        return view('home')->with($data);
    }

    public function update(Request $request)
    {
        return Fee::updateFee($request);
    }

    public function store(FeeRequest $request, $id)
    {
    	$fee = Fee::where('patient_id', $id)
                    ->whereBetween('created_at', array(date('Y-m-d'), date('Y-m-d 23:59:59')))
                    ->first();
                    
        if ($fee) {
            return "Cannot add Fee more than once on the same day";
        }

        $fee = new Fee;

        $fee->patient_id = $id;
        $fee->name = $request->name;
        $fee->entry_date = date('Y-m-d', strtotime($request->entry_date));
        $fee->start_date = date('Y-m-d', strtotime($request->start_date));
        $fee->end_date = date('Y-m-d', strtotime($request->end_date));
        $fee->total_amount = $request->amount;
        $fee->duration = $request->duration;
        $fee->valid_months = $request->duration;
        $fee->cre = $request->cre;
        $fee->source_id = $request->source;
        $fee->created_by = Auth::user()->employee->name;
        $fee->save();

        $patient = Patient::find($id);

        
        LeadStatus::saveStatus($patient->lead, 5);

        $status = "Fee saved";

        return $status;
    }
}
