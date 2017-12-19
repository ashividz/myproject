<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Database\QueryException;

use App\Models\Lead;
use App\Models\BreakAdjustment;
use App\Models\Fee;
use App\Models\PatientBreak;
use App\Models\Patient;
use App\Models\PatientHerb;
use App\Models\Tag;
use App\Models\PatientTag;
use App\Models\PatientNote;
use App\Models\Diet;
use App\Models\Prakriti;
use App\Models\PrakritiQuestion;
use App\Models\PatientPrakriti;
use App\Models\Email;
use App\Models\EmailTemplate;
use App\Models\User;

use App\Support\Helper;

use App\Http\Requests\PatientTagRequest;

use Auth;
use DB;
use Mail;
use Carbon;
use DateTime;

class PatientController extends Controller
{
    protected $menu;
    protected $daterange;
    protected $start_date;
    protected $end_date;
    protected $prakriti_email_template_id;

    public function __construct()
    {
        $this->menu = "patient";
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y/m/01 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y/m/d 23:59:59');
        $this->prakriti_email_template_id = 28;
    }

    public function viewPrakriti($id)
    {
    	$questions = PrakritiQuestion::get();


        $data = array(
            'menu'      	=> $this->menu,
            'section'  	 	=> 'audit',
            'questions'  	=> $questions,
            'i'         	=>  '1'
        );

        return view('home')->with($data);
    }

    /*public function medical($id)
    {
        $patient = Patient::find($id);

        $data = array(
            'menu'          => $this->menu,
            'section'       => 'partials.medical',
            'patient'       =>  $patient
        );

        return view('home')->with($data);
    }*/

    public function updateHerb($id, Request $request)
    {
        if(PatientHerb::active($id, $request->state))
        {
            return "Successfully updated";
        }
    }


    public function deleteHerb($id)
    {
        if(PatientHerb::destroy($id))
        {
            return "Successfully deleted";
        }

        return "Error";
    }

    public function tags($id)
    {
        $patient = Patient::find($id);
        $tags = PatientTag::where('patient_id', $id)->get();

        $data = array(
            'menu'          => $this->menu,
            'section'       => 'partials.tag',
            'patient'       =>  $patient,
            'tags'          =>  $tags
        );

        return view('home')->with($data);
    }

    public function saveTag(PatientTagRequest $request, $id)
    {
        try {

            $tag = new PatientTag;

            $tag->patient_id = $id;
            $tag->tag_id = $request->tag;
            $tag->note = $request->note;
            $tag->created_by = Auth::user()->employee->name;
            $tag->save();

        } catch (Illuminate\Database\QueryException $e) {
            echo $e;
        }

        return $this->tags($id);
    }

    public function notes($id)
    {
        $patient = Patient::find($id);
        $notes = PatientNote::where('patient_id', $id)
                ->orderBy('id', 'desc')
                ->get();

        $data = array(
            'menu'          => $this->menu,
            'section'       => 'partials.notes',
            'patient'       =>  $patient,
            'notes'          =>  $notes
        );

        return view('home')->with($data);
    }

    public function saveNote(Request $request, $id)
    {
        try {

            $note = new PatientNote;

            $note->patient_id = $id;
            $note->text = $request->note;
            $note->created_by = Auth::user()->employee->name;
            $note->save();

        } catch (Illuminate\Database\QueryException $e) {
            echo $e;
        }

        return $this->notes($id);
    }



    public function prakriti($id)
    {
        $patient = PatientPrakriti::prakriti($id);

        $questions = PrakritiQuestion::get();

        $data = array(
            'menu'          => 'patient',
            'section'       => 'partials.prakriti',
            'patient'       =>  $patient,
            'questions'     =>  $questions,
            'i'             => '1'
        );

        return view('home')->with($data);
    }

    public function savePrakriti(Request $request, $id)
    {

        $count = PrakritiQuestion::count();

        for ($i=1; $i <= $count; $i++) {
            if(isset($request->answer[$i])) {
                $prakriti = PatientPrakriti::firstOrNew(array('patient_id' => $id, 'question_id' => $i ));
                $prakriti->question_id = $i;
                $prakriti->prakriti_id = $request->answer[$i];
                $prakriti->save();
            }
        }

        $patient = PatientPrakriti::prakriti($id);
        $data = array(
            'message'       =>  '<li>Prakriti Saved</li>',
            'status'        =>  'success'
        );

        if (Auth::user()->hasRole('doctor')){
            if(self::sendPrakritiEmail($patient)) {
                $data["message"] .= '<li> Prakriti Report Sent</li>';
            }
        }



        //PatientPrakriti::calculatePrakriti($id);

        //dd($request);
        return redirect('patient/'.$id.'/prakriti')->with($data);
    }

    public function dispositions($id)
    {

        $patient = Patient::find($id);

        $lead = $patient->lead;

        $dept =  1;
        if (Auth::user()->hasRole('cre') || Auth::user()->hasRole('sales')) {
           $dept =  1;
        }
        elseif (Auth::user()->hasRole('doctor') || Auth::user()->hasRole('nutritionist') || Auth::user()->hasRole('service')) {
           $dept =  2;
        }

        $data = array(
            'menu'          =>  'patient',
            'section'       =>  'partials.dispositions',
            'dept'          =>  $dept,
            'lead'          =>  $lead,
            'patient'       =>  $patient
        );

        return view('home')->with($data);
    }

    public function details($id)
    {

        $patient = Patient::with('fees', 'diets')->find($id);

        $lead = Lead::with('patient', 'patient.fee', 'patient.fees', 'patient.diets', 'patient.primaryNtr', 'patient.secondaryNtr', 'patient.doctors')
                ->with('status')
                ->find($patient->lead_id);

        if (!$lead) {
            echo "Lead does not exist";
            die();
        }

        $data = array(
            'menu'          =>  'patient',
            'section'       =>  'partials.details',
            'lead'          =>  $lead,
            'patient'       =>  $patient
        );

        return view('home')->with($data);
    }

    public function emails($id)
    {
        $patient = Patient::find($id);

        $lead = $patient->lead;

        $emails = Email::where('lead_id', $lead->id)
                    ->orderBy('created_at', 'DESC')
                    ->limit('20')
                    ->get();

        $templates = EmailTemplate::with('attachments')->get();

        $data = array(
            'menu'          => 'patient',
            'section'       => 'partials.email',
            'lead'          =>  $lead,
            'patient'       =>  $patient,
            'templates'     =>  $templates,
            'emails'        =>  $emails,
            'i'             =>  '1'
        );

        return view('home')->with($data);
    }

    public function advanceDiet(Request $request, $id)
    {
        $patient = Patient::find($id);

        if($patient) {
            $patient->advance_diet = $request->state;
            $patient->save();

            $msg = "Advance diet ";
            $msg .= $request->state ? "set" : "removed";
            $msg .= " for ".$patient->lead->name;

            return $msg;
        }

        return "Patient not found";
    }

    public function get(Request $request)
    {
        return Patient::with('suit')
                ->find($request->id);
    }


    public function calls($id)
    {
        $serviceUsers  = User::getUsersByRole('service');
        $doctors       = User::getUsersByRole('doctor');
        $serviceTLs    = User::getUsersByRole('service_tl');
        $nutritionists = User::getUsersByRole('nutritionist');

        $serviceIds = array_merge(
                $serviceUsers->pluck('id')->toArray(),
                $doctors->pluck('id')->toArray(),
                $serviceTLs->pluck('id')->toArray(),
                $nutritionists->pluck('id')->toArray()
            );
        $serviceUserNames   = User::whereIn('id',$serviceIds)->get()->pluck('username')->toArray();

        $serviceNames       = User::whereIn('id',$serviceIds)->with('employee')->get()->pluck('employee.name')->toArray();

        $patient = Patient::findOrFail($id);

        $lead    = Lead::with(['dialerphonedispositions' => function($query) use($serviceUserNames){
                    $query->with('user')
                    ->whereIn('username',$serviceUserNames)
                    ->orderBy('eventdate','desc')
                    ->limit(20);
                }])
                ->with(['dialermobiledispositions' => function($query) use($serviceUserNames){
                    $query->with('user')
                    ->whereIn('username',$serviceUserNames)
                    ->orderBy('eventdate','desc')
                    ->limit(20);;
                }])
                ->with(['dispositions'=> function($query) use($serviceNames) {
                    $query->whereIn('name',$serviceNames)
                    ->orderBy('created_at','desc')
                    ->limit(20);;
                }])
                ->with('patient')
                ->findOrFail($patient->lead_id);

        $dialer_dispositions = collect();
        foreach($lead->dialerphonedispositions as $d)
            $dialer_dispositions->push($d);
        foreach($lead->dialermobiledispositions as $d)
            $dialer_dispositions->push($d);
        $dialer_dispositions =  $dialer_dispositions->sortByDesc('eventdate')->unique()->take(20);

        $data = array(
            'menu'               =>  'lead',
            'section'            =>  'modals.dispositions',
            'lead'               =>  $lead,
            'dialer_dispositions'=>  $dialer_dispositions,
            'i'                  =>  '0'
        );

        return view('lead.modals.dispositions')->with($data);
    }

    private function sendPrakritiEmail($patient)
    {
        $template  = EmailTemplate::find($this->prakriti_email_template_id);
        $prakritis = Prakriti::get();
        $body = Helper::renderView($template->email,['patient'=>$patient,'prakritis'=>$prakritis]);

        Mail::send([], [], function($message) use ($body, $patient, $template)
        {
            $from = isset($template->from) ? $template->from : 'quality@nutrihealthsystems.com';

            $message->to($patient->lead->email, $patient->lead->name)
            ->subject($template->subject)
            ->from($from, 'Nutri-Health Systems' );

            //Add CC
            if (trim($patient->lead->email_alt) <> '') {
                $message->cc($patient->lead->email_alt, $name = null);
            }

            $message->setBody($body, 'text/html');

            //Add attachments
            foreach ($template->attachments as $attachment) {
                $message->attachData($attachment->file, $attachment->name);
            }
        });



        $email = new Email();
        $email->user_id = Auth::user()->id;
        $email->lead_id = $patient->lead->id;
        $email->template_id = $this->prakriti_email_template_id;
        $email->email = $body;

        $email->save();

        return true;
    }

    public function preferences(Request $request)
    {
        $patient = Patient::find($request->id);
        if(isset($request->sms)||isset($request->app)||isset($request->email))
        {
            $patient->sms = ($request->sms=='on'?1:0);
            $patient->email = ($request->email=='on'?1:0);
            $patient->app = ($request->app=='on'?1:0);
            $patient->save();
        }
        $data = array(
            'menu' => 'patient',
            'section' => 'partials.preference',
            'patient' => $patient
       );
       return view('home')->with($data);
    }

    public function break($id)
    {
        $patient = Patient::
                   with('cfee' ,'lead')
                    ->find($id);

        $start_date = $patient->cfee->start_date;
        $end_date   = $patient->cfee->end_date;
        $today = Carbon::now();

        $totalDietSend = Diet::where('patient_id' , $patient->id)
                         ->whereBetween('date_assign', [$start_date, $end_date])
                         ->count();
        $difference = $start_date->diff($today)->days - $totalDietSend;

        $totalBreakDay = PatientBreak::where('cart_id' , $patient->cfee->cart_id)
                  ->sum('break_days');

        $difference = $difference - $totalBreakDay;


        /*$break = BreakAdjustment::where('duration' , $patient->cfee->duration)
                ->first();*/

        $break = BreakAdjustment::where('from_duration' , '<=' , $patient->cfee->duration)
                                ->where('to_duration' , '>=' , $patient->cfee->duration )
                                ->first();
        $totalBreakDay = PatientBreak::where('cart_id' , $patient->cfee->cart_id)  // toatl break taken by patient  ;
                  ->sum('break_days');

        $break->remaing = $break->break_allow - $totalBreakDay ;

        $breaks = PatientBreak::with('lead')
                  ->where('cart_id' , $patient->cfee->cart_id)
                  ->get();


        $data = array(
            'menu' => 'patient',
            'section' => 'partials.break',
            'patient' => $patient,
            'i'       => 1,
            'difference' => $difference,
            'breaks'    => $breaks,
            'break'     => $break
       );
       return view('home')->with($data);
    }

    public function saveBreakAdjustment($break_days , $patient , $fdate , $tdate)
    {
        $fee = Fee::find($patient->cfee->id);
        $oldDate = Carbon::parse($patient->cfee->end_date)->addDays($break_days)->toDateTimeString();
        $fee->end_date = $oldDate;
        $fee->save();


        $break = new PatientBreak;
        $break->lead_id = $patient->lead->id;
        $break->patient_id = $patient->id;
        $break->break_days = $break_days;
        $break->cart_id   = $patient->cfee->cart_id;
        $break->start_date = $fdate;
        $break->end_date = $tdate;
        $break->created_by = Auth::user()->employee->name;
        $break->save();
         
        $patient->lead->month = $patient->break->months;
        $patient->lead->count = $patient->count + 1 ;
        $patient->lead->fdate = $fdate;
        $patient->lead->tdate = $tdate;
        $patient->lead->start_date = Carbon::parse($tdate)->addDays(1)->toDateTimeString();

        
        $this->sendEmail($patient->lead);

        return "Break Adjusted";
    }

    public function breakAdjustment($id  , Request $request)
    {
        $time = strtotime($request->start_date);
        $fdate = date("Y-m-d",$time);
        $time = strtotime($request->end_date);
        $tdate = date("Y-m-d",$time);
        $datetime1 = new DateTime($fdate);
        $datetime2 = new DateTime($tdate);
        $interval = $datetime1->diff($datetime2)->days + 1;

      // dd($interval);

        $patient = Patient::
                   with('cfee' ,'lead')
                    ->find($id);

        $start_date = $patient->cfee->start_date;
        $end_date   = $patient->cfee->end_date;
        $today = Carbon::now();

        $totalDietSend = Diet::where('patient_id' , $patient->id)
                         ->whereBetween('date_assign', [$start_date, $end_date])     ///toatal diet sent  ;
                         ->count();

        $difference = $start_date->diff($today)->days - $totalDietSend;           // total break days ;

        $break = BreakAdjustment::where('from_duration' , '<=' , $patient->cfee->duration)
                                ->where('to_duration' , '>=' , $patient->cfee->duration )
                                ->first();

        $totalBreakDay = PatientBreak::where('cart_id' , $patient->cfee->cart_id)  // toatl break taken by patient  ;
                  ->sum('break_days');

        $count = PatientBreak::where('cart_id' , $patient->cfee->cart_id)         // How many times client taken the break ;
                  ->count();

        $patient->break = $break;
        $patient->count = $count;

        if($break->turn > $count  && $break->break_allow > $totalBreakDay)
        {
            $left_days = $break->break_allow - $totalBreakDay;
            $difference = $interval;

            if($left_days < $difference)
            {
                $tdate = Carbon::parse($fdate)->addDays($left_days-1)->toDateTimeString();
                $this->saveBreakAdjustment($left_days , $patient , $fdate , $tdate );
            }
            else
            {
                $tdate = Carbon::parse($fdate)->addDays($difference-1)->toDateTimeString();
                $this->saveBreakAdjustment($difference , $patient ,$fdate , $tdate);
            }

        }
        return $this->break($id);
    }

    public function sendEmail($lead)
    {
        if(trim($lead->email) == '')
        {
            return false;
        }
        $data = array(
                'customer'  => $lead->name,
                'name'      => Auth::user()->employee->name,
                'lead'      => $lead,
            );

        Mail::send('templates.emails.break', $data, function($message) use ($lead)
        {
            $from = Auth::user()->hasRole('nutritionist') || Auth::user()->hasRole('service') || Auth::user()->hasRole('doctor') ? 'dietplan@nutrihealthsystems.co.in' : 'sales@nutrihealthsystems.com';

            $message->to($lead->email, $lead->name)
            ->subject('Break Adjustment')
            ->from($from, 'Nutri-Health Systems');

            //Add CC
            /*if (trim($lead->email_alt) <> '') {
                $message->cc($lead->email_alt, $name = null);
            }*/
        });

        return true;

    }
}
