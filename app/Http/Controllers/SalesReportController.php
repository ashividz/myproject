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
use App\Models\Channel;
use Carbon;
use DB;
use Session;
use Excel;

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

      public function viewCreLeadStatus2()
    {
        $data = array(
            'menu'          =>  'sales',
            'section'       =>  'reports.TL_wise_lead_conversion2'
        );

        return view('home')->with($data);
    }

      public function viewChannelWiseLead()
    {
        $data = array(
            'menu'          =>  'sales',
            'section'       =>  'reports.channel_wise_lead_conversion'
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
        if($request->user_id > 0)
        $cres = User::getUsersByRole('cre', $request->user_id);
        else
        $cres =  User::getUsersByRole('cre');
        foreach ($cres as $cre) {
            $leads = Lead::with('patient', 'patient.fee', 'dispositions')
                    //->where('cre_name', $cre->name)
                    ->whereBetween('created_at', array($start_date, $end_date))
                    //->where('created_by', 'like', $cre->name)
                    ->where('cre_name', $cre->name)
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

         public function creLeadConversionWithChurned(Request $request)
    {
        $start_date = $request->start_date ? $request->start_date : Carbon::now()->subDays(30); 
        $end_date = $this->end_date;
        $conversion_last_date = $request->conversion_last_date;
        //echo $start_date . " - ". $end_date."<br>";
        if($request->user_id > 0)
        $cres = User::getUsersByRole('cre', $request->user_id);
        else
        $cres =  User::getUsersByRole('cre');
        foreach ($cres as $cre) {
            $leads = Lead::where('cre_name', $cre->name)
                            ->whereHas('cres', function ($q) use($start_date, $end_date){
                                $q->whereBetween('created_at', Array($start_date, $end_date));
                            })
                            /*->Join('lead_cre as lc', 'lc.lead_id', '=', 'marketing_details.id')
                            ->where('lc.cre', $cre->name)
                            ->whereBetween('lc.created_at', Array($start_date, $end_date))*/
                            ->whereBetween('marketing_details.created_at', array($start_date, $end_date))
                            ->get(); 

            $cre->leads = $leads->count();

            
            $cnt2 = 0;
            $converted_c = 0;

          

            $fees = Fee::where(function($r) use($cre) {
                              $r->where('cre_id', $cre->id)
                              ->orWhere('cre', $cre->name);
                            });


            $fees = $fees->whereBetween('created_at', array($request->conversion_start_date, $request->conversion_end_date));
            $fees = $fees->groupBy('patient_id')->get()->count();

            

            $cre->converted = $fees;//$converted_c;
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



    public function channelWiseLeadConversion(Request $request)
    {
        $start_date = $request->start_date ? $request->start_date : Carbon::now()->subDays(30); 
        $end_date = $this->end_date;
        $conversion_last_date = $request->conversion_last_date;
        //echo $start_date . " - ". $end_date."<br>";

        $channels = Channel::with('sources')->orderBy('id')->get();
//dd($channels);
        foreach ($channels as $channel) {
                        $sources = [];
                        foreach ($channel->sources as $source) {
                            $sources[] = $source->id;
                        }
                        $channel->source_ids = $sources; 
                    }
        if($request->user_id > 0)
        $cres = User::getUsersByRole('cre', $request->user_id);
        else
        $cres =  User::getUsersByRole('cre');
        foreach ($cres as $cre) {
            /*$leads = Lead::with('patient', 'patient.fee', 'dispositions')
                    ->Join('lead_cre as lc', 'lc.lead_id', '=', 'marketing_details.id')
                    ->where('lc.cre', $cre->name)
                    ->whereBetween('lc.created_at', Array($start_date, $end_date));

            if($request->create_assign=='created')
                $leads = $leads->whereBetween('marketing_details.created_at', array($start_date, $end_date));

            $leads = $leads->get(); */
            //dd($cre);

            $leads = Lead::where('cre_name', $cre->name)
                          ->whereHas('cres', function ($q) use($start_date, $end_date){
                                $q->whereBetween('created_at', Array($start_date, $end_date));
                            });
                             /*->join('lead_cre as lc', 'lc.lead_id', '=', 'marketing_details.id')
                            ->where('lc.cre', $cre->name)
                            ->whereBetween('lc.created_at', Array($start_date, $end_date));*/

            if ($request->create_assign == 'created')
               $leads = $leads->whereBetween('created_at', Array($start_date, $end_date));
            
            $leads = $leads->get();

            $cre->leads = $leads->count();
            
           
            $cnt2 = 0;
            $converted_c = 0;
            $channel_leads = array_fill(0, 7, 0);
            $leadsrcs = [];
            foreach ($leads as $lead) {
                $i = 0;
                foreach ($channels as $channel) {
                    if(in_array($lead->source_id,  $channel->source_ids))
                        $channel_leads[$i]++;
                    
                    $i++;
                      }
                if(is_null($lead->source_id) || $lead->source_id=='')
                    $channel_leads[6]++;
                
            }
           
            $cre->channels = $channel_leads;

           /* $fees = Fee::where(function($r) use($cre) {
                              $r->where('fees_details.cre_id', $cre->id)
                              ->orWhere('fees_details.cre', $cre->name);
                            });*/
            
            $fees = Fee::join('patient_details as p', 'p.id', '=', 'fees_details.patient_id')
                             ->join('lead_cre as lc', 'lc.lead_id', '=', 'p.lead_id')
                             ->join('marketing_details as m', 'm.id', '=', 'p.lead_id')
                             ->where(function($s) use($cre) {
                                  $s->where('lc.user_id', $cre->id)
                                  ->orWhere('lc.cre', $cre->name);
                                })
                             ->whereBetween('lc.created_at', Array($start_date, $end_date));

                             

            if ($request->create_assign == 'assigned')             
                 $fees = $fees->whereRaw('fees_details.created_at > lc.created_at');
            else
                $fees = $fees->whereBetween('m.created_at', Array($start_date, $end_date));

                $fees = $fees->whereBetween('fees_details.created_at', array($request->conversion_start_date, $request->conversion_end_date));
               

            $fees = $fees->groupBy('fees_details.patient_id')->get();
           
            $channel_conversion = array_fill(0, 7, 0);
           
            foreach($fees as $fee)
            {    $i = 0;
                 foreach ($channels as $channel) {
                    if(in_array($fee->source_id,  $channel->source_ids))
                        $channel_conversion[$i]++;
                    
                     $i++;
                      }
                 if(is_null($fee->source_id) || $fee->source_id=='')
                    $channel_conversion[6]++;
            }

            $cre->channel_conversions = $channel_conversion;

            $cre->converted = $fees->count();//$converted_c;
            
        }
        Session::put('ChannelWiseCreatedAssigned_cres', $cres);

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

         $fees = Fee::join('patient_details as p', 'p.id', '=', 'fees_details.patient_id')
                             ->join('lead_cre as lc', 'lc.lead_id', '=', 'p.lead_id')
                             ->join('marketing_details as m', 'm.id', '=', 'p.lead_id')
                             ->where(function($s) use($user) {
                                  $s->where('lc.user_id', $user->id)
                                  ->orWhere('lc.cre', $user->employee->name);
                                })
                             ->whereBetween('lc.created_at', Array($start_date, $end_date));

        if ($request->create_assign == 'assigned')             
            $fees = $fees->whereRaw('fees_details.created_at > lc.created_at');
        else
            $fees = $fees->whereBetween('m.created_at', Array($start_date, $end_date));
                       
           
        $fees = $fees->whereBetween('fees_details.created_at', array($request->conversion_start_date, $request->conversion_end_date));
         

       
        $fees = $fees->groupBy('patient_id')->get();
                       
                   
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

    public function LeadsIncludeChurned(Request $request, $id)
    {

        $user = User::find($id);
        $start_date = $request->start_date ? $request->start_date : Carbon::now()->subDays(30); 
        $end_date = $request->end_date ? $request->end_date : Carbon::now();


        $leads = Lead::with('source.master', 'cre', 'status', 'disposition')
                            ->with(['dispositions' => function($q) use($user) {
                                    $q->where('name', 'like', $user->employee->name);
                                }])
                            ->with('cre')->where('cre_name', $user->employee->name)
                            ->whereHas('cres', function ($q) use($start_date, $end_date){
                                $q->whereBetween('created_at', Array($start_date, $end_date));
                            });
                    //->where('cre_name', $cre->name)
                    
                    //->where('created_by', 'like', $cre->name)
        if ($request->create_assign != 'assigned') 
          $leads =  $leads->whereBetween('marketing_details.created_at', Array($start_date, $end_date));
        
       
                
               /* ->leftJoin('lead_cre as lc', 'lc.lead_id', '=', 'marketing_details.id')
                ->whereNotNull('lc.id')
                ->where('lc.cre', $user->employee->name)
                ->whereBetween('lc.created_at', Array($start_date, $end_date))*/
        $leads =  $leads->select('marketing_details.*')
                ->get(); 

      //dd($leads);
      
     
        $data = array(
            'leads'     =>  $leads,
            'i'         =>  1
        );

        return view('sales.modal.leads')->with($data);
    }


    public function churnedConverted(Request $request, $id)
    {
        $user = User::find($id);
        $start_date = $request->start_date ? $request->start_date : Carbon::now()->subDays(30); 
        $end_date = $request->end_date ? $request->end_date : Carbon::now();

        $fees = Fee::where(function($r) use($user) {
                              $r->where('cre_id', $user->id)
                              ->orWhere('cre', $user->employee->name);
                            });

                
        
        $fees = $fees->whereBetween('fees_details.created_at', array($request->conversion_start_date, $request->conversion_end_date));

        $fees = $fees->groupBy('patient_id')->get();
                       
                   
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

    public function downloadChannelCreatedAssigned(Request $request)
    {
        $cres = Session::get('ChannelWiseCreatedAssigned_cres');
        if (!$cres->isEmpty()) {
            Excel::create('ChannelWiseCreatedAssigned', function($excel) use($cres) {

                $excel->sheet('ChannelWiseCreatedAssigned', function($sheet) use($cres) {
                    //$sheet->fromArray($carts);
                        

                    $sheet->appendRow(array(
                           'Name', 
                           'Leads',
                           'Converted', 
                           'Conversion %',
                           'New',
                           'Reference',
                           'Rejoin',
                           'Upgrade',
                           'Corporate',
                           'Events',
                           'Unknown'                       
                    ));

                    $channelLeads = array_fill(0, 7, 0);
                    $channelConverted = array_fill(0, 7, 0);
                    $channelPerc = array_fill(0, 7, 0);
                    $total_leads = 0;
                    $total_converted = 0;
                    $total_conversion = 0;
                    foreach ($cres as $cre) 
                    {
                        for ($i = 0; $i < 7; $i++) 
                            {   
                                
                                $channelLeads[$i] = $channelLeads[$i] + $cre->channels[$i];
                                $channelConverted[$i] = $channelConverted[$i] + $cre->channel_conversions[$i];
                                $channelPerc[$i] = number_format(($channelLeads[$i] > 0)?($channelConverted[$i]/$channelLeads[$i]*100): 0, 2);
                            }
                            $total_leads += $cre->leads;
                            $total_converted += $cre->converted;
                            $total_conversion = number_format(($total_leads > 0)?($total_converted/$total_leads*100): 0, 2);
                    }

                    foreach ($cres as $cre) {

                        $converted_perc = 0;
                        if($cre->leads > 0)
                        $converted_perc = number_format(($cre->converted/$cre->leads*100), 2);

                        $sheet->appendRow(array(
                            $cre->name,
                            $cre->leads,
                            $cre->converted,
                            $converted_perc,

                            $cre->channels[0],
                            $cre->channel_conversions[0],
                            ($cre->channels[0] > 0)?number_format(($cre->channel_conversions[0]/$cre->channels[0]*100), 2): 0,

                            $cre->channels[1],
                            $cre->channel_conversions[1],
                            ($cre->channels[1] > 0)?number_format(($cre->channel_conversions[1]/$cre->channels[1]*100), 2): 0,

                            $cre->channels[2],
                            $cre->channel_conversions[2],
                            ($cre->channels[2] > 0)?number_format(($cre->channel_conversions[2]/$cre->channels[2]*100), 2): 0,

                            $cre->channels[3],
                            $cre->channel_conversions[3],
                            ($cre->channels[3] > 0)?number_format(($cre->channel_conversions[3]/$cre->channels[3]*100), 2): 0,

                            $cre->channels[4],
                            $cre->channel_conversions[4],
                            ($cre->channels[4] > 0)?number_format(($cre->channel_conversions[4]/$cre->channels[4]*100), 2): 0,

                            $cre->channels[5],
                            $cre->channel_conversions[5],
                            ($cre->channels[5] > 0)?number_format(($cre->channel_conversions[5]/$cre->channels[5]*100), 2): 0,

                            $cre->channels[6],
                            $cre->channel_conversions[6],
                            ($cre->channels[6] > 0)?number_format(($cre->channel_conversions[6]/$cre->channels[6]*100), 2): 0,
                           
                        ));

                    }

                    $sheet->appendRow(array(
                            'Total',
                            $total_leads,
                            $total_converted,
                            $total_conversion,

                            $channelLeads[0],
                            $channelConverted[0],
                            $channelPerc[0],

                            $channelLeads[1],
                            $channelConverted[1],
                            $channelPerc[1],

                            $channelLeads[2],
                            $channelConverted[2],
                            $channelPerc[2],

                            $channelLeads[3],
                            $channelConverted[3],
                            $channelPerc[3],

                            $channelLeads[4],
                            $channelConverted[4],
                            $channelPerc[4],

                            $channelLeads[5],
                            $channelConverted[5],
                            $channelPerc[5],

                            $channelLeads[6],
                            $channelConverted[6],
                            $channelPerc[6],
                        ));

                   $sheet->mergeCells('E1:G1');
                   $sheet->mergeCells('H1:J1');
                   $sheet->mergeCells('K1:M1');
                   $sheet->mergeCells('N1:P1');
                   $sheet->mergeCells('Q1:S1');
                   $sheet->mergeCells('T1:V1');
                   $sheet->mergeCells('W1:Y1');

                   $sheet->cell('E1', function($cell) {
                        $cell->setValue('New');
                    });

                    $sheet->cell('H1', function($cell) {
                        $cell->setValue('Reference');
                    });

                    $sheet->cell('K1', function($cell) {
                        $cell->setValue('Rejoin');
                    });

                    $sheet->cell('N1', function($cell) {
                        $cell->setValue('Upgrade');
                    });

                    $sheet->cell('Q1', function($cell) {
                        $cell->setValue('Corporate');
                    });

                    $sheet->cell('T1', function($cell) {
                        $cell->setValue('Events');
                    });

                    $sheet->cell('W1', function($cell) {
                        $cell->setValue('Unknown');
                    });

                });
            })->download('xls');;
        }
    }

}
