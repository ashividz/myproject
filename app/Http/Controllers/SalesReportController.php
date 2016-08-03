<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Lead;
use App\Models\Status;
use App\Models\Cart;
use App\Models\Fee;
use Carbon;
use DB;

class SalesReportController extends Controller
{   
    protected $start_date;
    protected $end_date;

    public function __construct(Request $request)
    {
        $this->start_date = $request->start_date ? $request->start_date : Carbon::now()->format('Y-m-d'); 
        $this->end_date = $request->end_date ? $request->end_date : Carbon::now();

    }

    public function viewLeadStatus()
    {
        $data = array(
            'menu'          =>  'sales',
            'section'       =>  'reports.lead_status'
        );

        return view('home')->with($data);
    }

     public function viewCreLeadStatus()
    {
        $data = array(
            'menu'          =>  'sales',
            'section'       =>  'reports.TL_wise_lead_conversion'
        );

        return view('home')->with($data);
    }

    public function leadStatusReport(Request $request)
    {
        $start_date = $request->start_date ? $request->start_date : Carbon::now()->subDays(30); 
        $end_date = $this->end_date;

        //echo $start_date . " - ". $end_date."<br>";
        $cres = User::getUsersByRole('cre', $request->user_id);
        foreach ($cres as $cre) {
            $leads = Lead::with(['dispositions' => function($q) use($cre) {
                        $q->where('name', 'like', $cre->name);
                    }])
                    ->where('cre_name', $cre->name)
                    ->whereNotIn('status_id', [6])
                    ->whereBetween('created_at', array($start_date, $end_date))
                    ->get(); 

            $cre->leads = $leads->count();

            $last = 0;
            $calls = 0;
            $never = 0;
            foreach ($leads as $lead) {
                if (!$lead->dispositions->isEmpty()) {
                    
                    if ($lead->dispositions->last()->created_at <= Carbon::now()->subDays(4)) {
                        $last++;
                    }
                    if ($lead->dispositions->count() < 4 ) {
                        $calls++;
                    }
                } else {
                    $never++;
                }
            }

            $cre->last = $last;
            $cre->calls = $calls;
            $cre->never = $never;
            $counts = Status::select(DB::raw('m_lead_status.id, count(m.id) as cnt'))
                        ->leftJoin('marketing_details as m', function($join) use($start_date, $end_date, $cre) {
                            $join->on('m_lead_status.id', '=', 'm.status_id')
                            ->where('created_at', '>=', $start_date)
                            ->where('created_at', '<=', $end_date)
                            ->where('cre_name', 'like', $cre->name);
                        })
                        ->groupBy('m_lead_status.id')                        
                        ->get();

            $cre->counts = $counts;

            /*try {

                $dialer_dispositions = DB::connection('pgsql')->table('ct_recording_log as crl')
                            ->where('crl.phonenumber', '=', $lead->phone);
            } catch (\Exception $e) {
            
                Session::flash("message", "Error connecting with Dialer Database");
                Session::flash("status", "error");
            }   */  
        }

        //dd($cres);
        return $cres;
    }

        public function creConversionReport(Request $request)
    {
        $start_date = $request->start_date ? $request->start_date : Carbon::now()->subDays(30); 
        $end_date = $this->end_date;
        $conversion_last_date = $request->conversion_last_date;
        //echo $start_date . " - ". $end_date."<br>";
        $cres = User::getUsersByRole('cre', $request->user_id);
        foreach ($cres as $cre) {
            $leads = Lead::with('patient', 'patient.fee', 'dispositions')
                    //->where('cre_name', $cre->name)
                    ->whereBetween('created_at', array($start_date, $end_date))
                    //->where('created_by', 'like', $cre->name)
                    ->where('cre_name', 'like', $cre->name)
                    ->get(); 

            $cre->leads = $leads->count();

            $last = 0;
            $calls = 0;
            $never = 0;
            $converted_c = 0;

            foreach ($leads as $lead) {
              if($lead->patient && !is_null($lead->patient->fee) && $lead->patient->fee->cre==$cre->name && $lead->patient->fee->created_at < $conversion_last_date)
                $converted_c++;
            }

            $cnt = Fee::where(function($r) use($cre) {
                              $r->where('cre_id', $cre->id)
                              ->orWhere('cre', $cre->name);
                            })
                        ->whereBetween('created_at', array($start_date, $end_date))
                        ->groupBy('patient_id')->get()->count();


            $cre->converted = $cnt;//$converted_c;
             /* $converted = Lead::with('patient.fee')
                                ->whereHas('patient.fee', function($q) use($conversion_last_date) {
                                // Query the department_id field in status table
                                 $q->where('created_at', '<', $conversion_last_date); // '=' is optional
                                })

                                //->where('cre_name', $cre->name)
                                ->whereBetween('created_at', array($start_date, $end_date))
                                ->where('created_by', $cre->name)
                                ->get(); 
            $cre->converted = $converted->count();*/
            $cre->last = $last;
            $cre->calls = $calls;
            $cre->never = $never;
            $counts = Status::select(DB::raw('m_lead_status.id, count(m.id) as cnt'))
                        ->leftJoin('marketing_details as m', function($join) use($start_date, $end_date, $cre) {
                            $join->on('m_lead_status.id', '=', 'm.status_id')
                            ->where('created_at', '>=', $start_date)
                            ->where('created_at', '<=', $end_date)
                            ->where('cre_name', 'like', $cre->name);
                        })
                        ->groupBy('m_lead_status.id')                        
                        ->get();

            $cre->counts = $counts;

            /*try {

                $dialer_dispositions = DB::connection('pgsql')->table('ct_recording_log as crl')
                            ->where('crl.phonenumber', '=', $lead->phone);
            } catch (\Exception $e) {
            
                Session::flash("message", "Error connecting with Dialer Database");
                Session::flash("status", "error");
            }   */  
        }

        //dd($cres);
        return $cres;
    }

    public function performance()
    {
        $data = [
            'menu'      => 'reports',
            'section'   =>  'sales.performance'
        ];

        return view('home')->with($data);
    }

      public function convertedLeads(Request $request, $id)
    {

        $user = User::find($id);
        $start_date = $request->start_date ? $request->start_date : Carbon::now()->subDays(30); 
        $end_date = $request->end_date ? $request->end_date : Carbon::now();

          $fees = Fee::where(function($r) use($user) {
                              $r->where('cre_id', $user->id)
                              ->orWhere('cre', $user->employee->name);
                            })
                        ->whereBetween('created_at', array($start_date, $end_date))
                        ->groupBy('patient_id')->get();
                //dd($fees);    
         foreach($fees as $fee)
         {
            //dd($fee->patient->lead_id);
            $leads[] = Lead::with('source.master', 'cre', 'status', 'disposition')
                            ->with(['dispositions' => function($q) use($user) {
                                    $q->where('name', 'like', $user->employee->name);
                                }])
                            ->find($fee->patient->lead_id);
            
         }              
      
     
        $data = array(
            'leads'     =>  $leads,
            'i'         =>  1
        );

        return view('sales.modal.leads')->with($data);
    }

    
}
