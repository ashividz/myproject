<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\YuWoW\User;
use App\Models\YuWoW\Healthtrack;
use App\Models\YuWoW\Fitness;
use App\Models\YuWoW\Deviation;
use App\Models\YuWoW\Diary;

use App;
use App\Models\Patient;
use App\Models\Days365;
use DateTime;
use DateInterval;
use DatePeriod;

class YuWoWController extends Controller
{
    protected $daterange;
    protected $start_date;
    protected $end_date;

    public function __construct(Request $request)
    {
    
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y/m/01 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y/m/d 23:59:59');
        $this->nutritionist = isset($request->user) ? $request->user : '';        
    }
    public function index($id)
    {
        $start_date = $this->start_date;
        $end_date  = $this->end_date;

        $lead = '';/*User::
                ->leftJoin('yuwow_alpha_1_0.dt_cust_healthtrack AS h', function($join)
                    {
                        $join->on('d.dch_cust_id', '=', 'h.dch_cust_id');
                        $join->on('h.dch_date_recording', '=', 'd.dch_date');
                    })
                ->leftJoin('yuwow_alpha_1_0.dt_cust_health_diary AS d', function($join)
                    {
                        $join->on('d.dch_cust_id', '=', 'h.dch_cust_id');
                        $join->on('h.dch_date_recording', '=', 'd.dch_date');
                    })
                >find($id);

        /*with('yuwow')
                ->with(['yuwow.healthtrack' => function($q) use ($start_date, $end_date) {
                        $q->whereBetween('dch_date_recording', array($start_date, $end_date));
                    }])
                ->with(['yuwow.deviation' => function($q) use ($start_date, $end_date) {
                        $q->whereBetween('dcd_date', array($start_date, $end_date));
                    }])
                ->with(['yuwow.diary' => function($q) use ($start_date, $end_date) {
                        $q->whereBetween('dch_date', array($start_date, $end_date));
                    }])
                ->with(['yuwow.fitness' => function($q) use ($start_date, $end_date) {
                        $q->whereBetween('dcf_date', array($start_date, $end_date));
                    }])
                ->find($id);*/
        
        dd($lead);
        $begin = new DateTime( $this->start_date );
        $end = new DateTime( $this->end_date );
        $end = $end->modify( '+1 day' );
        $interval = new DateInterval('P1D');

        $daterange = new DatePeriod($begin, $interval, $end);               

        $data = array(

            'menu'          =>  'patient',
            'section'       =>  'index',
            'lead'          =>  $lead,
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'daterange'     =>  $daterange
            );

        return view('home')->with($data);
    }

    public function progress($id)
    {
        $patient = Patient::find($id);

        if($patient) {

            $noOfDays = (strtotime($this->end_date) - strtotime($this->start_date)) / (60 * 60 * 24) + 1;

            $days = Days365::limit($noOfDays)->get();

            $user = User::where('user_email', trim($patient->lead->email))->first();

            //dd($user);
            
            if($user) {
                foreach ($days as $day) {
                    $date = date('Y-m-d', strtotime("+".$day->day." days", strtotime($this->start_date)));
                    //$date_end = date('Y-m-d 23:59:59', strtotime($date_start));

                    $day->date = $date;

                    $weight = Healthtrack::where('dch_cust_id', $user->Id)
                                    ->where('dch_date_recording', $date)
                                    ->select('dch_weight')
                                    ->first();

                    $fitness = Fitness::where('dcf_cust_id', $user->Id)
                                    ->where('dcf_date', $date)
                                    ->select('dcf_fitness_brief')
                                    ->first();

                    $deviation = Deviation::where('dcd_cust_id', $user->Id)
                                    ->where('dcd_date', $date)
                                    ->select('dcd_deviation_notes')
                                    ->first();

                    $diary = Diary::where('dch_cust_id', $user->Id)
                                    ->where('dch_date', $date)
                                    ->select('dch_health_diary')
                                    ->first();

                    $day->weight = isset($weight->dch_weight) ? $weight->dch_weight : '';
                    $day->fitness = isset($fitness->dcf_fitness_brief) ? $fitness->dcf_fitness_brief : '';
                    $day->deviation = isset($deviation->dcd_deviation_notes) ? $deviation->dcd_deviation_notes : '';
                    $day->diary = isset($diary->dch_health_diary) ? $diary->dch_health_diary : '';
                }

                //dd($days);

                $data = array(

                    'menu'          =>  'patient',
                    'section'       =>  'partials.yuwow',
                    'patient'       =>  $patient,
                    'start_date'    =>  $this->start_date,
                    'end_date'      =>  $this->end_date,
                    'days'          =>  $days
                );

                return view('home')->with($data);
            }
            return "YuWoW details not found";
        }

        return "Patient not found";
    }

    public function customerFeedback()
    {
        $start_date = $this->start_date;
        $end_date  = $this->end_date;
        
        $feedbacks = Diary::getFeedbacks($start_date,$end_date);   

        $data = array(
            'menu'          =>  'service',
            'section'       =>  'reports.yuwow_feedback',
            'feedbacks'     =>  $feedbacks,
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,            
            );

        return view('home')->with($data);
    }

    public function yuwowUsers()
    {
        if($this->nutritionist != '')
            $yuwowUsers = Patient::getYuWoWUsers($this->nutritionist);       
        else
            $yuwowUsers=null;
        $users      = App\Models\User::getUsersByRole('nutritionist');
        
        $data = array(
                    'menu'              =>  'service',
                    'section'           =>  'yuwowUsers',
                    'yuwowUsers'        =>   $yuwowUsers,
                    'users'             =>   $users,
                    'name'              =>   $this->nutritionist,
                );
        
        return view('home')->with($data);
    }
    
}
