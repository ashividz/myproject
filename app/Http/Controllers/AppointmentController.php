<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use App\Models\Appointment;
use App\Models\LeadStatus;
use App\Models\CallDisposition;
use Carbon;
use Auth;

class AppointmentController extends Controller
{
  

    public function modal($id)
    {
        $lead = Lead::find($id);

        $data = array(
            'lead'   => $lead
        );

        $led = Lead::has('patient')->find($id);
    if($led){
        return view('lead.modals.appointment')->with($data);
 
}
else{
    return view('lead.modals.appointmentno')->with($data);
}      
return view('lead.modals.appointment')->with($data);
//return view('lead.modals.appointment')->with($data);
       //return $lead->patient->id;
    }

    public function store(Request $request, $id)
    {
    
       // dd($request);

        $lead = Lead::has('patient')->find($id);
      
     //   dd($lead->patient->doctor);


        if ($lead) {
          //  $post->firstname= $request->input('firstname');
            $appointment = new Appointment;
            $appointment->lead_id = $request->input('lead_id');
            $date= Carbon::parse($request->input('date'));
            $appointment->date=$date->format('Y-m-d');
            $appointment->patient_id=$lead->patient->id;
            $appointment->doctor_name=$lead->patient->doctor;
            $appointment->time = $request->input('usr_time');
            $appointment->description = $request->input('remark');
            $appointment->save();
            
            return back();
        }
        
        return "Lead does not exist";

 
       // view('lead.modals.appointment');
    }
}
