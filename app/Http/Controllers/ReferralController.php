<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Lead;
use App\Models\Patient;
use App\Models\Fee;

class ReferralController extends Controller
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
	public function index()
	{
		$fees = Fee::with('patient')
				->where('source_id', '27')
				->whereBetween('entry_date', array($this->start_date, $this->end_date))
				->get();
		//dd($fees);

		$data = array(
            'menu'              =>  'marketing',
            'section'           =>  'referral',
            'start_date'		=>	$this->start_date,
            'end_date'			=>	$this->end_date,
            'fees'				=>	$fees,
            'i'					=> 	'1'
        );
        return view('home')->with($data);
	}
}