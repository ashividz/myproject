<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Patient;
use App\Models\User;
use Auth;
use DB;

class NutritionistController extends Controller
{
    protected $nutritionist;
    protected $daterange;
    protected $start_date;
    protected $end_date;
    protected $menu = "nutritionist";

    public function __construct(Request $request)
    {
    
        $this->nutritionist = isset($request->user) ? $request->user : Auth::user()->employee->name;
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date('Y-m-d', strtotime('-5 days'));
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y-m-d', strtotime('+5 days'));
        
    } 
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $section = "dashboard";
        $data = array(
            'section'           => $section,
            'menu'              => $this->menu
        );
        return view('home')->with($data);
    }

    public function viewUpgradeList()
    {
        $patients = Patient::getUpgradeList('Sneha Singh');
        //dd($patients);

        $data = array(            
            'menu'              => $this->menu,
            'section'           => 'upgrade',
            'patients'          => $patients
        );
        return view('home')->with($data);
    }

    public function patients()
    {
        $days = round((strtotime($this->end_date) - strtotime($this->start_date)) / (60 * 60 * 24));

        $patients = Patient::getActivePatients($this->nutritionist);

        $secondaryPatients = Patient::select('patient_details.*')
                    ->with('diets', 'fee', 'suit')                    
                    /*->join(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                            $join->on('patient_details.id', '=', 'f.patient_id');
                        })

                    ->leftJoin('suit_ntsuit AS s', function($join) {
                            $join->on('f.clinic', '=', 's.clinic');
                            $join->on('f.registration_no', '=', 's.registration_no');
                        })
                    ->where('end_date', '>=', date('Y-m-d'))*/
                    ->whereHas('fee', function($query){
                        $query->where('end_date', '>=', DB::RAW('CURDATE()'));
                    })
                    ->join('marketing_details as m', 'm.id', '=', 'patient_details.lead_id')
                    ->where('secondary_nutritionist', $this->nutritionist)
                    ->orderBy('name')
                    ->get();
        //dd($days);

        $users = User::getUsersByRole('nutritionist');

        $data = array(            
            'menu'              =>  $this->menu,
            'section'           =>  'patients',
            'users'             =>  $users,
            'name'              =>  $this->nutritionist,
            'start_date'        =>  $this->start_date,
            'end_date'          =>  $this->end_date,
            'patients'          =>  $patients,
            'secondaryPatients' =>  $secondaryPatients,
            'days'              =>  $days,
            'x'                 =>  '1',
            'y'                 =>  '1',
            'z'                 =>  '1'
        );
        return view('home')->with($data);
    }

    public function programEnd()
    {
        $users = User::getUsersByRole('nutritionist');

        $patients = Patient::getActivePatients($this->nutritionist);

        $data = array(            
            'menu'              =>  $this->menu,
            'section'           =>  'program_end',
            'users'             =>  $users,
            'name'              =>  $this->nutritionist,
            'start_date'        =>  $this->start_date,
            'end_date'          =>  $this->end_date,
            'patients'          =>  $patients,
            'i'                 =>  '1'
        );
        return view('home')->with($data);
    }

    public function audit()
    {
        $users = User::getUsersByRole('nutritionist');

        $patients = Patient::getActivePatients($this->nutritionist);

        $data = array(            
            'menu'              =>  $this->menu,
            'section'           =>  'audit',
            'users'             =>  $users,
            'name'              =>  $this->nutritionist,
            'i'                 =>  '1',
            'patients'          =>  $patients
        );
        return view('home')->with($data);   
    }


}
