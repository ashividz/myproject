<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\EmailTemplate;
use App\Models\Lead;

use Carbon;
use DB;
use App\Support\SMS;

class SMSController extends Controller
{
    public function patients()
    {
        $data = [
            'menu'      =>  'marketing',
            'section'   =>  'sms.patients'
        ];

        return view('home')->with($data);
    }

    public function birthday()
    {
        $data = [
            'menu'      =>  'marketing',
            'section'   =>  'sms.birthday'
        ];

        return view('home')->with($data);
    }

    public function leads()
    {
        $data = [
            'menu'      =>  'marketing',
            'section'   =>  'sms.leads'
        ];

        return view('home')->with($data);
    }

    /** For Bulk SMS**/
    

    public function getPatients(Request $request)
    {
        

        $query = Lead::select('marketing_details.id', 'marketing_details.name', 'city', 'f.start_date', 'f.end_date')
                    ->join('patient_details as p', 'p.lead_id', '=', 'marketing_details.id')
                    ->leftJoin(DB::raw('(SELECT id, patient_id, start_date, end_date FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                        $join->on('p.id', '=', 'f.patient_id');
                    })
                    //->whereIn('marketing_details.id' , $DOB)
                    ->where('country', 'IN')
                    ->has('dnc', '<', 1);

        if ($request->patient == 'active') {     

            $query = $query->where('end_date', '>=', Carbon::now());

        } elseif ($request->patient == 'inactive') {
            $query = $query->where('end_date', '<', Carbon::now())
                    ->whereBetween('f.end_date', [$request->start_date, $request->end_date]);
        }
        $leads = $query->get();
        return $leads;
    }

    public function getbirthday(Request $request)
    {
        $users = DB::select('SELECT id , name, dob  FROM marketing_details WHERE DATE(CONCAT(YEAR(CURDATE()), RIGHT(dob, 6))) BETWEEN  DATE_SUB(CURDATE(), INTERVAL 0 DAY) AND  DATE_ADD(CURDATE(), INTERVAL 7 DAY) ;');

        $DOB = [] ;
        foreach ($users as $user) {
            $DOB[] = $user->id;
        } 

        $query = Lead::select('marketing_details.id', 'marketing_details.name', 'city', 'f.start_date', 'f.end_date')
                    ->join('patient_details as p', 'p.lead_id', '=', 'marketing_details.id')
                    ->leftJoin(DB::raw('(SELECT id, patient_id, start_date, end_date FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                        $join->on('p.id', '=', 'f.patient_id');
                    })
                    ->whereIn('marketing_details.id' , $DOB)
                    ->where('country', 'IN')
                    ->has('dnc', '<', 1);

        if ($request->patient == 'active') {     

            $query = $query->where('end_date', '>=', Carbon::now());

        } elseif ($request->patient == 'inactive') {
            $query = $query->where('end_date', '<', Carbon::now())
                    ->whereBetween('f.end_date', [$request->start_date, $request->end_date]);
        }
        $leads = $query->get();
        return $leads;
    }

    public function send(Request $request)
    {   
        $i = 0;
        $message =  $request->message;
        $sms = new SMS();
        $data = [];
        foreach ($request->ids as $id) {
            $i++;
            $lead = Lead::find($id);
            $mobile =  $lead->mobile ? : $lead->phone;
            if ($mobile) {
                $sms_response = $sms->send($message, $mobile);
                $data[$i]['name'] = $lead->name;
                $data[$i]['sms'] = $sms_response;
            }
        }

        return json_encode($data);
    }

    
}