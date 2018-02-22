<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Patient;
use App\Models\User;
use App\Models\Lead;
use App\Models\PatientWeight;
use App\Models\NutritionistLeave;
use App\Models\Employee;
use Auth;
use DB;
use Carbon;

class NutritionistController extends Controller
{
    protected $nutritionist;
    protected $daterange;
    protected $start_date;
    protected $end_date;
    protected $allUsers;
    protected $menu = "nutritionist";

    public function __construct(Request $request)
    {
    
        $this->nutritionist = isset($request->user) ? $request->user : Auth::user()->employee->name;
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date('Y-m-d', strtotime('-5 days'));
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y-m-d', strtotime('+5 days'));
        $this->allUsers = $request->user=='' ? true : false;
        
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

        //return $patients;
        

        $secondaryPatients = Patient::select('patient_details.*')
                    ->with('diets', 'fee','cfee','suit')                    
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
        $secondaryActivePatient = Patient::getSecondryActivePatients($this->nutritionist);

        $nutritionistid = [] ;

        $nutritionistIds =  NutritionistLeave::getNutritionistId();

        foreach ( $nutritionistIds as $nid) {
            # code...
            $nutritionistid[] = $nid->nutritionist_id;
        }


        $names = Employee::whereIn('id' , $nutritionistid)->get();

       $nutritionistName = [] ;

        foreach ($names as $name) {
            $nutritionistName[] = $name->name;
        }
        
       // return $nutritionistName;
        
        $users = DB::select('SELECT id , name, dob  FROM marketing_details WHERE DATE(CONCAT(YEAR(CURDATE()), RIGHT(dob, 6))) BETWEEN  DATE_SUB(CURDATE(), INTERVAL 0 DAY) AND  DATE_ADD(CURDATE(), INTERVAL 7 DAY) ;');

        $DOB = [] ;
        foreach ($users as $user) {
            $DOB[] = $user->id;
        } 
        $users = User::getUsersByRole('nutritionist');

        $data = array(            
            'menu'                      =>  $this->menu,
            'section'                   =>  'patients',
            'users'                     =>  $users,
            'name'                      =>  $this->nutritionist,
            'start_date'                =>  $this->start_date,
            'end_date'                  =>  $this->end_date,
            'patients'                  =>  $patients,
            'secondaryPatients'         =>  $secondaryPatients,
            'secondaryActivePatient'    => $secondaryActivePatient,
            'days'                      =>  $days,
            'names'                     =>  $nutritionistName,
            'x'                         =>  '1',
            'y'                         =>  '1',
            'dob'                       =>  $DOB,
           
        );
        return view('home')->with($data);
    }

    public function programEnd()
    {
        $users = User::getUsersByRole('nutritionist');

        if($this->allUsers)
            $patients = Patient::getProgramEnd($this->start_date,$this->end_date);
        else
            $patients = Patient::getProgramEnd($this->start_date,$this->end_date,$this->nutritionist);

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

    public function performance()
    {
        $users = User::getUsersByRole('nutritionist');

        //$patients = Patient::getActivePatients($this->nutritionist);

        

        $weightLoss = Patient::where('nutritionist' , $this->nutritionist)
        ->whereHas('fees',function($query) {
            $query->where('end_date','>=',DB::raw('curdate()'));
        })
        ->with('fees','lead','lead.programs')
        ->whereHas('lead.programs', function($q) {
                        $q->where('programs.id', 1);
                    })
        ->limit(env('DB_LIMIT'))
        ->get();

        $weightLoss = PatientWeight::weightLoss($weightLoss);

        $data = array(            
            'menu'              =>  $this->menu,
            'section'           =>  'performance',
            'users'             =>  $users,
            'name'              =>  $this->nutritionist,
            'i'                 =>  '1',
            'patients'          =>  $weightLoss
        );
        return view('home')->with($data);   
    }

    public function Breakadjestment()
    {
       $users = Patient::select('patient_details.*')
                ->with('lead', 'fee' , 'diet')
                
                ->join('marketing_details as m', 'patient_details.lead_id', '=', 'm.id')
                ->join(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                    $join->on('patient_details.id', '=', 'f.patient_id');
                })
                ->where('end_date', '>=', date('Y-m-d'))
                ->orderBy('age')
                ->get();
          $id = [];  
        foreach ($users as $user) {
            if($user->diet)
                if($user->diet->date_assign <= Carbon::now()->subDays(7)->toDateString())
                {
                    $id[] = $user->lead->id;
                }
           // echo "</br>";
        }

        /*$patients = Patient::getDietNotStarted();
        
        $pid = [];

        foreach ($patients as $patient) {
            
            $pid[] = $patient->lead_id; 
        }

       $result = array_diff($id, $pid);*/

       
        
       $coustomers = Lead::with('patient','patient.cfee', 'patient.diet')
                        ->whereIn('id' , $id)
                        ->get();

        $data = array(            
            'menu'              =>  $this->menu,
            'section'           =>  'breakadjestment',
            'coustomers'        =>  $coustomers,
            'i'                 =>  '1'
           
        );
        return view('home')->with($data);

        
    }


}
