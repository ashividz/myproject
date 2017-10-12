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
use GuzzleHttp\Client;

use DB;
use App;
use App\Models\Patient;
use App\Models\Lead;
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

                    $htrack = Healthtrack::where('dch_cust_id', $user->Id)
                                    ->where('dch_date_recording', $date)
                                    ->select('*')
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

                    if(isset($htrack)){
                        $day->weight      = $htrack->dch_weight ? $htrack->dch_weight : '';
                        $day->body_fat    = $htrack->body_fat ? $htrack->body_fat : '';
                        $day->muscle_mass = $htrack->muscle_mass ? $htrack->muscle_mass : '';
                        $day->bone_weight = $htrack->bone_weight ? $htrack->bone_weight : '';
                        $day->hydration   = $htrack->hydration ? $htrack->hydration : '';
                    }
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

    public function yuwowUsageReport()
    {
        $onDateServicedClients =  Patient::select(DB::raw('count(patient_details.id) onDateServicedClients, ifnull(patient_details.nutritionist,"") as nutritionist'))
                ->whereHas('fee', function($query){
                    $query->where('end_date', '>=', DB::RAW('DATE_ADD(CURDATE(), INTERVAL -1 DAY)'))->where('start_date', '<', DB::RAW('CURDATE()'));
                })
                ->groupBy(DB::raw('ifnull(patient_details.nutritionist,"")'))
                ->get();
        
        $yuwowUsers =  Patient::select(DB::raw('count(patient_details.id) yuwowUsers, ifnull(patient_details.nutritionist,"") as nutritionist'))
                ->whereHas('fee', function($query){
                    $query->where('end_date', '>=', DB::RAW('DATE_ADD(CURDATE(), INTERVAL -1 DAY)'))->where('start_date', '<', DB::RAW('CURDATE()'));
                })
                ->where(function($query){
                    $query->has('lead.yuwow.deviation')
                    ->orHas('lead.yuwow.diary')
                    ->orHas('lead.yuwow.fitness')
                    ->orHas('lead.yuwow.healthtrack');
                })
                ->groupBy(DB::raw('ifnull(patient_details.nutritionist,"")'))
                ->get();
        
        //make a key value pair of nutritionist
        $onDateServicedClients = array_combine(
            array_map(function($o) { return $o['nutritionist']; }, $onDateServicedClients->toArray() ),
            array_map(function($o) { return array('onDateServicedClients' => $o['onDateServicedClients']); }, $onDateServicedClients->toArray())
        );

        $yuwowUsers = array_combine(
            array_map(function($o) { return $o['nutritionist']; }, $yuwowUsers->toArray()),
            array_map(function($o) { return array('yuwowUsers' => $o['yuwowUsers']); }, $yuwowUsers->toArray())
        );

        //merge onDateServicedClients with yuwowUsers
        $yuwow = array_merge_recursive($onDateServicedClients,$yuwowUsers);
        $yuwowUsage = array();
        
        foreach ($yuwow as $nutritionist => $yuwowRecord) {
            if($nutritionist!=''){
                $obj = (object) [];
                $obj->nutritionist            = $nutritionist;
                $obj->onDateServicedClients   = $yuwowRecord['onDateServicedClients'];
                $obj->yuwowUsers              = isset($yuwowRecord['yuwowUsers']) ? $yuwowRecord['yuwowUsers'] : 0;            
                $yuwowUsage[]                 = $obj;
            }
        }

        $serviceTLs =array_column(App\Models\User::getUsersByRole('service_tl')->toArray(),'name');
        
        $data = array(
                    'menu'              =>  'service',
                    'section'           =>  'reports.yuwowUsageReport',
                    'yuwowUsage'        =>   $yuwowUsage,
                    'serviceTLs'        =>   $serviceTLs,                    
                );

        return view('home')->with($data);
    }
    public function yuwowNotification(Request $request)
    {

        //     dd($request);
            
        //     define( 'API_ACCESS_KEY', 'AAAALPfWeKg:APA91bEI1NK1YqI-aJwBa-_g3NNgkbR00zlw9tmSYN0tSfklOHHqWHIw0kD2G44PV3H7h3GlxkVVSRHoixgE69TYSXZYuIBzlesIFYoPjZTzwROhWiSRhpxho1FMHHoYYa8lbl-8i8vE' );
        //    // print_r($device_id);
        //     $users = User::where('device_id', '<>' ,'null')
        //              ->get();
        //     $message = $request->message;
        //     $title  = $request->title;
        //     $device_id;
        //    /* $client = new Client();
        //     $result = $client->request('POST', 'https://portal.yuwow.com/index.php/notification/saveMessage', [
        //             'form_params' => [
        //             'message' => json_encode($request->message)
        //             ]
        //             ]);*/
        //     $states = [];
        //     foreach ($users as $user) 
        //     {
        //         $lead = Lead::where('email' , $user->user_email)->first();
                
        //         if($lead)
        //         {
        //             if($lead->state == "IN.07" && $request->notification == "NCR")
        //             {

                    

        //                 if(strpos($user->device_id, '?') !== false)
        //                 {
        //                     $pos = strpos($user->device_id, '?');
        //                     $result = substr($user->device_id, 0, $pos);
        //                     $device_id = $result;
        //                     $registrationIds = $device_id;
        //                 }
        //                 else
        //                 {
        //                     $registrationIds = $user->device_id;
        //                 }
                            
        //                 // prep the bundle
        //                 $msg = array
        //                 (
        //                     'body'      => $message,
        //                     'title'     => $title,
        //                     'vibrate'   => 1,
        //                     'sound'     => 1,
        //                     'click_action' => 'com.yuwow.OPEN_ACTIVITY_1',
        //                 );
                        
        //                 $fields = array
        //                 (
        //                     'registration_ids'  => array ( $registrationIds ), 
        //                     'notification' =>  $msg 
        //                 );
                         
        //                 $headers = array
        //                 (
        //                     'Authorization: key=' . API_ACCESS_KEY,
        //                     'Content-Type: application/json'
        //                 );
                         
        //                 $ch = curl_init();
        //                 curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        //                 curl_setopt( $ch,CURLOPT_POST, true );
        //                 curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        //                 curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        //                 curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        //                 curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        //                 $result = curl_exec($ch );
        //                 curl_close( $ch );
        //             }
        //             if($lead->state != "IN.07" && $lead->country == "IN" && $request->notification == "PAN")
        //             {

                    

        //                 if(strpos($user->device_id, '?') !== false)
        //                 {
        //                     $pos = strpos($user->device_id, '?');
        //                     $result = substr($user->device_id, 0, $pos);
        //                     $device_id = $result;
        //                     $registrationIds = $device_id;
        //                 }
        //                 else
        //                 {
        //                     $registrationIds = $user->device_id;
        //                 }
                            
        //                 // prep the bundle
        //                 $msg = array
        //                 (
        //                     'body'      => $message,
        //                     'title'     => $title,
        //                     'vibrate'   => 1,
        //                     'sound'     => 1,
        //                     'click_action' => 'com.yuwow.OPEN_ACTIVITY_1',
        //                 );
                        
        //                 $fields = array
        //                 (
        //                     'registration_ids'  => array ( $registrationIds ), 
        //                     'notification' =>  $msg 
        //                 );
                         
        //                 $headers = array
        //                 (
        //                     'Authorization: key=' . API_ACCESS_KEY,
        //                     'Content-Type: application/json'
        //                 );
                         
        //                 $ch = curl_init();
        //                 curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        //                 curl_setopt( $ch,CURLOPT_POST, true );
        //                 curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        //                 curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        //                 curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        //                 curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        //                 $result = curl_exec($ch );
        //                 curl_close( $ch );
        //             }
        //             if($lead->country != "IN" && $lead->country != " " && $request->notification == "INT")
        //             {

                    

        //                 if(strpos($user->device_id, '?') !== false)
        //                 {
        //                     $pos = strpos($user->device_id, '?');
        //                     $result = substr($user->device_id, 0, $pos);
        //                     $device_id = $result;
        //                     $registrationIds = $device_id;
        //                 }
        //                 else
        //                 {
        //                     $registrationIds = $user->device_id;
        //                 }
                            
        //                 // prep the bundle
        //                 $msg = array
        //                 (
        //                     'body'      => $message,
        //                     'title'     => $title,
        //                     'vibrate'   => 1,
        //                     'sound'     => 1,
        //                     'click_action' => 'com.yuwow.OPEN_ACTIVITY_1',
        //                 );
                        
        //                 $fields = array
        //                 (
        //                     'registration_ids'  => array ( $registrationIds ), 
        //                     'notification' =>  $msg 
        //                 );
                         
        //                 $headers = array
        //                 (
        //                     'Authorization: key=' . API_ACCESS_KEY,
        //                     'Content-Type: application/json'
        //                 );
                         
        //                 $ch = curl_init();
        //                 curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        //                 curl_setopt( $ch,CURLOPT_POST, true );
        //                 curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        //                 curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        //                 curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        //                 curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        //                 $result = curl_exec($ch );
        //                 curl_close( $ch );
        //             }
        //         }
        //     }
        //     return redirect('yuwow/sendNotification');
        // }
        define( 'API_ACCESS_KEY', 'AAAALPfWeKg:APA91bEI1NK1YqI-aJwBa-_g3NNgkbR00zlw9tmSYN0tSfklOHHqWHIw0kD2G44PV3H7h3GlxkVVSRHoixgE69TYSXZYuIBzlesIFYoPjZTzwROhWiSRhpxho1FMHHoYYa8lbl-8i8vE' );
       // print_r($device_id);
        $users = User::where('device_id', '<>' ,'null')
                 ->get();
        $message = $request->message;
        $title  = $request->title;
        $device_id;
        $client = new Client();
        $result = $client->request('POST', 'https://portal.yuwow.com/index.php/notification/saveMessage', [
                'form_params' => [
                'message' => json_encode($request->message)
                ]
                ]);
        foreach ($users as $user) 
        {
            if(strpos($user->device_id, '?') !== false)
            {
                $pos = strpos($user->device_id, '?');
                $result = substr($user->device_id, 0, $pos);
                $device_id = $result;
                $registrationIds = $device_id;
            }
            else
            {
                $registrationIds = $user->device_id;
            }
                
            // prep the bundle
            $msg = array
            (
                'body'      => $message,
                'title'     => $title,
                'vibrate'   => 1,
                'sound'     => 1,
                'click_action' => 'com.yuwow.OPEN_ACTIVITY_1',
            );
            
            $fields = array
            (
                'registration_ids'  => array ( $registrationIds ), 
                'notification' =>  $msg 
            );
             
            $headers = array
            (
                'Authorization: key=' . API_ACCESS_KEY,
                'Content-Type: application/json'
            );
             
            $ch = curl_init();
            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
            $result = curl_exec($ch );
            curl_close( $ch );
        }
        return redirect('yuwow/sendNotification');
    }

    
}
