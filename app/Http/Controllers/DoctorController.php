<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\CallDisposition;
use App\Models\Patient;
use Auth;
use DB;

class DoctorController extends Controller
{
    protected $menu;
    protected $doctor;
    protected $daterange;
    protected $start_date;
    protected $end_date;

    public function __construct(Request $request)
    {
        $this->menu = "doctor";        
        $this->doctor = isset($request->user) ? $request->user : Auth::user()->employee->name;
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y/m/d 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y/m/d 23:59:59');
 
    }

    public function index()
    {
        $data = array(
            'menu'          =>  $this->menu,
            'section'       =>  'index'
        );

        return view('home')->with($data);
    }
    
    public function calls()
    {
        $users = User::getUsersByRole('doctor');

        $calls = CallDisposition::getDispositionsByUser($this->doctor, $this->start_date, $this->end_date);
        
        //dd($calls);
        $data = array(
            'menu'          =>  $this->menu,
            'section'       =>  'calls',
            'users'         =>  $users,
            'name'          =>  $this->doctor,
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'calls'         =>  $calls,
            'i'             =>  '0'
        );

        return view('home')->with($data);
    }

    public function patients()
    {
        $patients = Patient::select("patient_details.*")
                    ->leftJoin(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                        $join->on('patient_details.id', '=', 'f.patient_id');
                    })
                    ->where('f.end_date', '>=', date('Y-m-d'))
                    ->where('doctor', $this->doctor)
                    ->get();

        $users = User::getUsersByRole('doctor');

        $data = array(
            'menu'          =>  $this->menu,
            'section'       =>  'patients',
            'users'         =>  $users,
            'patients'      =>  $patients,
            'name'          =>  $this->doctor,
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'i'             =>  '1'
        );

        return view('home')->with($data);
    }
}
