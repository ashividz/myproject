<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Lead;
use App\DND;
use Auth;
use App\Jobs\DndCheck;
use App\Models\DndJobRange;
use Log;

class DNDController extends Controller
{
	protected $daterange;
    protected $start_date;
    protected $end_date;

    public function __construct(Request $request)
    {
    
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y-m-01 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y-m-d 23:59:59');
        
    } 
	public function index()
	{	

		$leads = Lead::
				whereNull('phone_dnd')
				->orWhereNull('mobile_dnd')
				->limit(env('DB_LIMIT'))
				->get();

				//dd($leads);
		
		foreach ($leads as $lead) {
			
			$this->check($lead);			
		}
	}

	public function check($lead)
	{
		$dnd = new DND;

		$status = 0;
        if($dnd->scrub($lead->phone) == "Y"){

            echo '<p>Phone : '.$lead->id.$lead->name;
            Lead::setPhoneDNDStatus($lead, 1);
            $status = 1;
        } elseif ($dnd->scrub($lead->phone) == "N") {
            Lead::setPhoneDNDStatus($lead, 0);
            $status = 0;
        }


        
        if(trim($lead->mobile) != trim($lead->phone))
        {
            if($dnd->scrub($lead->mobile) == "Y"){

                Lead::setMobileDNDStatus($lead, 1);
                echo '<p>Mobile : '.$lead->id.$lead->name;

            } elseif ($dnd->scrub($lead->mobile) == "N") {

                Lead::setMobileDNDStatus($lead, 0);
            }
        }
        else
        {   
            Lead::setMobileDNDStatus($lead, $status);
        }
	}

	public function show($id)
	{
		$lead = Lead::find($id);

		$data = array(
            'menu'      => 'lead',
            'section'   => 'dnc',
            'lead'		=>	$lead
        );

        return view('home')->with($data);
	}

	public function store(Request $request, $id)
	{
		$dnc = LeadDnc::where('lead_id', $id)->first();

		if (!$dnc) {
			$dnc = new LeadDnc;
			$dnc->lead_id = $id;
			$dnc->remark = trim($request->remark);
			$dnc->created_by = Auth::id();
			$dnc->save();
		}

		return redirect()->action('LeadController@showContactDetails', [$id]);
	}

	public function scrub($phone)
	{
		$status = 0;
		$dnd = new DND;
		$status = $dnd->scrub($phone);
		// if($dnd->scrub($phone)){
		// 	$status = 1;
		// } 

		echo $status;
	}
}