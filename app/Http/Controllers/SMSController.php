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

    public function leads()
    {
        $data = [
            'menu'      =>  'marketing',
            'section'   =>  'sms.leads'
        ];

        return view('home')->with($data);
    }

    /** For Bulk SMS**/
    public function getLeads(Request $request)
    {
        $start_date = isset($request->start_date) ? Carbon::parse($request->start_date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');

        $end_date = isset($request->end_date) ? Carbon::parse($request->end_date)->format('Y-m-d 23:59:59') : Carbon::now(); 

        $query = Lead::with('status')
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->where('country', 'IN')
                    ->whereIn('status_id', $request->status_id)
                    ->has('dnc', '<', 1)
                    ->has('patient', '<', 1);

        $leads = $query->get();
        return $leads;
    }

    public function getPatients(Request $request)
    {
        $query = Lead::select('marketing_details.id', 'marketing_details.name', 'city', 'f.start_date', 'f.end_date')
                    ->join('patient_details as p', 'p.lead_id', '=', 'marketing_details.id')
                    ->leftJoin(DB::raw('(SELECT id, patient_id, start_date, end_date FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                        $join->on('p.id', '=', 'f.patient_id');
                    })
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