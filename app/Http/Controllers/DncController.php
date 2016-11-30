<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Lead;
use App\Models\LeadDnc;
use Auth;


class DncController extends Controller
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
		$dncs = LeadDnc::
				whereBetween('created_at', array($this->start_date, $this->end_date))
				->get();

		$data = array(
            'menu'      	=> 'marketing',
            'section'   	=> 'reports.dnc',
            'start_date'	=>	$this->start_date,
            'end_date'		=>	$this->end_date,
            'dncs'			=>	$dncs,
            'i'				=>	'1'
        );

        return view('home')->with($data);
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
}