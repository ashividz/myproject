<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use App\Models\Employee;
use App\Models\Patient;
use App\Models\Disposition;
use App\Models\DialerPush;
use App\Models\PredictiveJobRange;
use App\Models\DialerPushNew;
use App\Models\Status;
use App\Models\LeadCre;
use DB;
use Auth;
use App\Support\Helper;
use Log;
use App\Jobs\PredictiveDialer;
use App\Models\PredictiveCount;

define("DIALER_URI",  'http://192.168.1.203/test.ajax');


class DialerPushController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(Request $request)
    {   
        $this->limit = isset($request->limit) ? $request->limit : 2000;
        $this->list_id = "SALES16082016";//"sales06052016";
        $this->cre = isset($request->user) ? $request->user : Auth::user()->employee->name;
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";

        $this->start_date = isset($this->daterange[0]) ? date('Y-m-d 0:0:0', strtotime($this->daterange[0])) : date("2016-08-01 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y-m-d 23:59:59', strtotime($this->daterange[1])) : date('2016-08-30 23:59:59');
    }

    public function PreedictiveQueue(Request $request)
    {
      $job = null;
      if(isset($this->daterange) && $this->daterange!="")
        {
          $job = DB::select( DB::raw("SELECT * FROM jobs WHERE payload LIKE '%PredictiveDialer%'"));
            if(!$job)
            {
             $predictiveJobRange = PredictiveJobRange::get()->first();
             if(!$predictiveJobRange)
               $predictiveJobRange = new PredictiveJobRange();

             $predictiveJobRange->start_date  = $this->start_date;
             $predictiveJobRange->end_date = $this->end_date;
             $predictiveJobRange->last_step_date = $this->start_date;
             $predictiveJobRange->save();
             $dispos_date = $request->dispos_date;
             //$dispos_date = date('Y-m-d', strtotime($dispos_date));
             //$this->handle();
             $this->dispatch(new PredictiveDialer(Auth::id(), $dispos_date));
            }
        }

        $dispo_date = '';
         $data = array(
                'section'       => 'dialer_push_queue',
                'menu'          => 'lead',
                'start_date'    => $this->start_date,
                'end_date'      => $this->end_date,
                'limit'         => $this->limit,
                'dispo_date'    => $dispo_date,
                'job'           => $job,
                'i'             => 1
            ); 
      return view('home')->with($data);
    }


    public function isPredictiveJobRunning()
    {
        $job = DB::select( DB::raw("SELECT * FROM jobs WHERE payload LIKE '%PredictiveDialer%'") );
        $current_date = date('Y-m-d');
        if($job)
          return "1";
        else
        {
          $predictiveJobRange = PredictiveJobRange::get()->first();
          $start_date = $predictiveJobRange->start_date;
          $end_date = $predictiveJobRange->end_date;

          $leads = DialerPush::whereRaw("date(created_at) = '$current_date'")
                              ->whereBetween('lead_date', Array("$start_date", "$end_date"))
                              ->get()->count();

         /* $PredictiveCount = PredictiveCount::get();
          $leads = $PredictiveCount->first()->rows; */
                          
          return $leads;
        }
    }

    public function handle()
    {
       Log::info("Job Started ");
       $leads = null;
       $predictiveJobRange = PredictiveJobRange::get()->first();
       $end_date = $predictiveJobRange->end_date;
       $start_limit = $predictiveJobRange->last_step_date;
       $end_limit = date('Y-m-d 23:59:59',strtotime('+10 days',strtotime($start_limit)));
       $predictiveJobRange->last_step_date = $end_limit;
       $predictiveJobRange->save();
       Log::info("Job range saved");
       while($end_limit < $end_date)
       {
          
         if(isset($start_limit) && $start_limit!="")
          {
          $dispo_date = date('2016-07-31 23:59:59');
          $cur_date = date('Y-m-d 0:0:0');
          //dd( $this->end_date );
          $leads_qry = Lead::select('marketing_details.*')
              ->with('cre', 'disposition');
              
          $leads_qry->has('patient', '<', 1);        
          $leads_qry->has('dnc', '<', 1);  
           
         
          $leads_qry->whereBetween('marketing_details.created_at', array($start_limit, $end_limit))
                      ->where(function($q)  {
                              $q->where('marketing_details.country','=','IN')
                                ->orWhereNull('marketing_details.country')
                                ->orWhere('marketing_details.country','=', '');
                       });
            
              
           $leads = $leads_qry->orderBy('marketing_details.created_at')->get();

           $this->executex($leads);
                
                }

               $predictiveJobRange = PredictiveJobRange::get()->first();
              
               $start_limit = $predictiveJobRange->last_step_date;
               $end_limit = date('Y-m-d 23:59:59', strtotime('+10 days',strtotime($start_limit)));
               $predictiveJobRange->last_step_date = $end_limit;
               $predictiveJobRange->save();
            }
     }

      public function executex($leads)
      {   
        $list_id = "SALES16082016";
        foreach($leads as $lead)
        {
            $output= 'false2';
            $lead_id = $lead->id;
            $phone = $lead->phone;
            $lead_date = $lead->created_at;
            $phone = Helper::properMobile($phone);
            $pin1 = substr(trim($phone), 0, 2);
            $pin2 = substr(trim($phone), 0, 3);
            
            if($lead->cre)  
            $cre_name = $lead->cre->cre;
         
             //dd($username);
            //$cre_name = $request->cre_name[$i];
            //$dispo_date = $request->dispo_date[$i];
            //$dispo_remark = $request->dispo_remark[$i];
            //$callback = $request->callback[$i];
            //$username = $request->username[$i];
            //$push = $request->push[$i];
            //$cre_assign_date = $request->cre_assign_date[$i];
            //$lead_name = $request->lead_name[$i];
            //$lead_status = $request->lead_status[$i];
            //$source = $request->source[$i];
            
           
            //if($push==1)
            //{
            //$ch = curl_init("http://192.168.1.203/test.ajax");
            $encoded_params = "do=manualUpload&username=admin&password=contaquenv&campname=Sales_Outbound&skillname=ENGLISH&listname=$list_id&phone1=".$phone;
            
            /*curl_setopt($ch, CURLOPT_POSTFIELDS,  $encoded_params);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($ch);
            curl_close($ch);*/
            //}

          if($pin1 != "11" && $pin2 != "011")
            $output = file_get_contents("http://192.168.1.203/test.ajax?".$encoded_params);
          else
            $output = "Landline-No. Skipped";
            
           
            
            if($output == "Number added successfully.")
            {
                //$lead = Lead::find($lead_id);
                $dialer_push = New DialerPush;
                $dialer_push->lead_id = $lead_id;
                //$dialer_push->user = $username;
                //$dialer_push->name = $cre_name;
               
                $dialer_push->phone = $phone;
                $dialer_push->list_id =  $list_id;
                $dialer_push->created_by = Auth::id();
                $dialer_push->lead_date = $lead_date;
                $dialer_push->status = $output;
                $dialer_push->save();
            }
      }
 
    }

    /* public function index()
    {   
        

        $users = User::getUsersByRole('cre');

        //dd($users);
         $data = array(
            'section'       =>  'dialer',
            'menu'          =>  'lead',
            'start_date'    => $this->start_date,
            'end_date'      => $this->end_date,
            'users'      => $cre_list,
            'i'             =>  1
        );

        
        return view('home')->with($data);
    }*/
    public function getLeadsConsecutive(Request $request)
    {
      $users = User::getUsersByRole('cre');
        $cre = $request->user;
        $disop_date = date('Y-m-d 0:0:0', strtotime('-3 days'));
        $cur_date = date('Y-m-d 0:0:0');
        $list_id = $this->list_id;
        if(isset($request->limit))
            $this->limit = $request->limit;
        else
             $this->limit = 100;  


        $limit =10;

       

        $leads_qry =  Lead::with('dnc')
                        ->with('patient')
                        ->with('sources.master', 'status')
                        ->join(DB::raw("(select lead_id from (select lead_id,count(lead_id) as count,disposition_id  from( select lead_id,disposition_id,rank from ( select lead_id,disposition_id, @currank:=if(@curleadid = lead_id,@currank +1,1) as rank, @curleadid:=lead_id as l  from  (select lead_id,disposition_id,created_at from call_dispositions where created_at>=(current_date - interval 30 day))  as d order by lead_id, created_at desc  ) as d where (rank<=$limit and disposition_id in (3,4,5,6,10))  ) as dispose   group by lead_id) as ddd where count =$limit) AS cdp"), function($join) {
                                 $join->on('marketing_details.id', '=', 'cdp.lead_id');
                            })
                        ->where('status_id', '<>', 5)
                        ->where('country','=','IN');
                        if(isset($request->user))
                            $leads_qry->join(DB::raw("(SELECT * FROM lead_cre A WHERE cre = '$cre' and (deleted_at IS NULL OR deleted_at = '') and id = (SELECT MAX(id) FROM lead_cre B WHERE A.lead_id=B.lead_id)) AS c"), function($join) {
                                     $join->on('marketing_details.id', '=', 'c.lead_id');
                                });
                        else
                            $leads_qry->join(DB::raw("(SELECT * FROM lead_cre A WHERE (deleted_at IS NULL OR deleted_at = '') and id = (SELECT MAX(id) FROM lead_cre B WHERE A.lead_id=B.lead_id)) AS c"), function($join) {
                                     $join->on('marketing_details.id', '=', 'c.lead_id');
                                });
                        $leads_qry->select('marketing_details.*', 'c.created_at as cre_assign_date', 'c.cre as cre_name', 'c.cre as cre_name');
                        //->whereBetween('c.created_at', array($this->start_date, $this->end_date))
                        //->where('c.cre', $cre)
                        $leads_qry->orderBy('c.created_at','desc');
                        //->WhereNull('c.deleted_at')
                        $leads = $leads_qry->get();

        //dd($leads);
        $push_stats = [];
        foreach($leads as $lead)
        {

        //echo $lead->phone."<br>";
        $push_stat = $this->push($lead, $cre);
        if(array_filter($push_stat))
        $push_stats[]  = $push_stat;
        }
        
        
        $data = array(
            'section'       => 'dialer_pushstat',
            'menu'          => 'lead',
            'start_date'    => $this->start_date,
            'end_date'      => $this->end_date,
            'push_stats'    => $push_stats,
            'users'         => $users,
            'name'          => $this->cre,
            'limit'         => $this->limit,
            'i'             => 1
        ); 
        return view('home')->with($data);
       //$this->push("9582405381", "harsh");

    }

     public function getLeadsx(Request $request)
    {
       $users = User::getUsersByRole('cre');
       $leads = null;

       if(isset($this->daterange) && $this->daterange!="")
        {
        $cre = $request->user;
        $dispo_date = date('2016-06-15 0:0:0');
        $cur_date = date('Y-m-d 0:0:0');
        //dd( $this->end_date );
        $leads_qry = Lead::has('dnc', '<', 1)
            ->has('cre', '<', 1)
            ->has('disposition', '<', 1)
            ->has('patient', '<', 1)->get()->count();

       return ($leads_qry);
       }            

    }

     public function getLeads(Request $request)
    {
       $users = User::getUsersByRole('cre');
       $leads = null;

       if(isset($this->daterange) && $this->daterange!="")
        {
        $cre = $request->user;
        $dispo_date = date('2016-06-15 0:0:0');
        $cur_date = date('Y-m-d 0:0:0');
        //dd( $this->end_date );
        $leads_qry = Lead::select('marketing_details.*')
            ->with('cre', 'disposition')
            ->has('dnc', '=', 0);
            

         $leads_qry->leftJoin(DB::raw("(SELECT * FROM call_dispositions cd1 WHERE id = (SELECT MAX(id) FROM call_dispositions cd2 WHERE cd1.lead_id=cd2.lead_id)) AS cd"), function($join) {
                             $join->on('marketing_details.id', '=', 'cd.lead_id');
                            })

                     ->whereIn('cd.disposition_id', [2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14])
                     ->where('cd.created_at', '<', $dispo_date)
                     ->where(function($r) use($cur_date) {
                              $r->whereRaw("cd.callback < '$cur_date'")
                              ->orWhereNull('cd.callback');
                            }); 

      

             $leads_qry->leftJoin('patient_details as p', 'p.lead_id', '=', 'marketing_details.id')
                       ->leftjoin(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                                $join->on('p.id', '=', 'f.patient_id');
                            })->where(function($q)  {
                                    $q->whereNull('p.id')
                                    ->orWhere(function($r)  {
                                              $r->whereNull('f.id')
                                              ->orWhere('f.end_date', '<',  date('Y-m-d'));
                                          });
                                    });
           

            //$leads_qry->leftJoin('lead_dncs as d', 'd.lead_id', '=', 'marketing_details.id');
           
            //$leads_qry->leftJoin('dialer_push as dp', 'dp.lead_id', '=', 'marketing_details.id');
            $leads_qry->whereBetween('marketing_details.created_at', array($this->start_date, $this->end_date))
                        //->whereNull('p.id')
                        ->whereNull('d.id')
                        //->whereNull('dp.id')
                        //->where('dp.list_id', '<>' , $this->list_id)
                        ->where(function($q)  {
                                $q->where('marketing_details.country','=','IN')
                                  ->orWhereNull('marketing_details.country')
                                  ->orWhere('marketing_details.country','=', '');
                         });
          
            //->whereNotNull('marketing_details.source_id')
            
            
            $leads = $leads_qry->limit($this->limit)->get();
              }
        
            $data = array(
                'section'       => 'dialer_pushstat',
                'menu'          => 'lead',
                'start_date'    => $this->start_date,
                'end_date'      => $this->end_date,
                'leads'         => $leads,
                'users'         => $users,
                'name'          => $this->cre,
                'limit'         => $this->limit,
                'i'             => 1
            ); 
            return view('home')->with($data);
          
           
    }


    public function getLeads3(Request $request)
    {
        $users = User::getUsersByRole('cre');
        $cre = $request->user;
        $dispo_date = date('Y-m-d 0:0:0', strtotime('-15 days'));
        
        $leads_qry = Lead::select('marketing_details.*')
            ->with('cre', 'disposition');
             if(isset($request->user))
                    $leads_qry->join(DB::raw("(SELECT * FROM lead_cre A WHERE cre = '$cre' and (deleted_at IS NULL OR deleted_at = '') and id = (SELECT MAX(id) FROM lead_cre B WHERE A.lead_id=B.lead_id)) AS c"), function($join) {
                             $join->on('marketing_details.id', '=', 'c.lead_id');
                        });
                else
                    $leads_qry->join(DB::raw("(SELECT * FROM lead_cre A WHERE (deleted_at IS NULL OR deleted_at = '') and id = (SELECT MAX(id) FROM lead_cre B WHERE A.lead_id=B.lead_id)) AS c"), function($join) {
                             $join->on('marketing_details.id', '=', 'c.lead_id');
                        });
            $leads_qry->leftJoin('patient_details as p', 'p.lead_id', '=', 'marketing_details.id')
            ->leftJoin('lead_dncs as d', 'd.lead_id', '=', 'marketing_details.id')
            ->leftjoin(DB::raw('(SELECT id, lead_id, created_at FROM call_dispositions A WHERE A.created_at <= "2016-03-31 00:00:00" and id = (SELECT MAX(id) FROM call_dispositions B WHERE  A.lead_id=B.lead_id)) AS cd'), function($join) {
                $join->on('marketing_details.id', '=', 'cd.lead_id');
            })
            ->leftJoin('dialer_push as dp', 'dp.lead_id', '=', 'marketing_details.id')
            ->whereBetween('marketing_details.created_at', array($this->start_date, $this->end_date))
            ->whereNull('p.id')
            ->whereNull('d.id')
            //->whereNull('dp.id')
            ->where('cd.created_at', '<', $dispo_date);
            //->whereNotNull('marketing_details.source_id')
            
            
            $leads = $leads_qry->limit($this->limit)
            ->get();
        //dd($leads);

            //dd($leads);
        /*$push_stats = [];
        foreach($leads as $lead)
        {

            //echo $lead->phone."<br>";
            $push_stat = $this->push($lead, $cre);
            if(array_filter($push_stat))
            $push_stats[]  = $push_stat;
        }*/
        
        
        $data = array(
            'section'       => 'dialer_pushstat',
            'menu'          => 'lead',
            'start_date'    => $this->start_date,
            'end_date'      => $this->end_date,
            'leads'         => $leads,
            'users'         => $users,
            'name'          => $this->cre,
            'limit'         => $this->limit,
            'i'             => 1
        ); 
        return view('home')->with($data);
       //$this->push("9582405381", "harsh");
    }

 public function getLeads2(Request $request)
    {
        $users = User::getUsersByRole('cre');
        $cre = $request->user;
        $disop_date = date('Y-m-d 0:0:0', strtotime('-3 days'));
        $cur_date = date('Y-m-d 0:0:0');
        $list_id = $this->list_id;
        $leads = null;
        $username = null;
      if(isset($cre) && !is_null($cre))
      {
        $emp = Employee::where('name','LIKE',$cre)->first();
        $username = $emp->user->username;
           
        $leads = Lead::select('marketing_details.*')
                        ->with('disposition', 'cre')
                       
                        ->leftJoin('patient_details as p', 'p.lead_id', '=', 'marketing_details.id')
                        ->leftJoin('lead_dncs as d', 'd.lead_id', '=', 'marketing_details.id')
                        ->leftjoin(DB::raw("(SELECT * FROM dialer_push WHERE  name='$cre') as dp "), function($join) {
                                 $join->on('marketing_details.id', '=', 'dp.lead_id');
                            })
                        ->join(DB::raw("(SELECT * FROM lead_cre A WHERE cre = '$cre' and id = (SELECT MAX(id) FROM lead_cre B WHERE A.lead_id=B.lead_id)) AS c"), function($join) {
                             $join->on('marketing_details.id', '=', 'c.lead_id');
                            })
                        ->leftjoin(DB::raw("(SELECT * FROM call_dispositions cd1 WHERE  id = (SELECT MAX(id) FROM call_dispositions cd2 WHERE cd1.lead_id=cd2.lead_id)) AS cd"), function($join) {
                                 $join->on('marketing_details.id', '=', 'cd.lead_id');
                                     
                            })
                        
                        ->whereNull('p.id')
                        ->whereNull('d.id')
                        ->whereNull('dp.id')

                        ->whereIn('cd.disposition_id',[2, 3, 4, 5, 6, 7, 8, 10, 12, 13, 14])
                        ->where(function($q) use($cur_date) {
                              $q->whereRaw("cd.callback < '$cur_date'")
                              ->orWhereNull('cd.callback');
                            })

                       
                        /*->where(function($q) use($cur_date) {
                            $q->whereIn('cd.disposition_id',[2, 3, 4, 5, 6, 7, 8, 10, 12, 13, 14])
                              ->where(function($r) use($cur_date)  {
                            $r->whereRaw("cd.callback < '$cur_date'")
                              ->orWhereNull('cd.callback');
                            });
                            })*/
                        ->where(function($q)  {
                            $q->where('cd.id','>','0')
                              ->orWhereNull('cd.id');
                            })
                        ->whereNotIn('marketing_details.status_id', [4, 5])
                        ->whereBetween('marketing_details.created_at', array($this->start_date, $this->end_date))  
                        //->where('marketing_details.status_id', '<>', 5)
                        //->whereBetween('marketing_details.created_at', array($this->start_date, $this->end_date))
                        
                        //->orderBy('cd.created_at','desc')
                        //->WhereNull('c.deleted_at')
                        //->where('country','=','IN')
                        ->where(function($q)  {
                            $q->where('marketing_details.country','=','IN')
                              ->orWhereNull('marketing_details.country')
                              ->orWhere('marketing_details.country','=','');
                            })
                        ->limit($this->limit)->get();
       }
//dd($leads);
            $data = array(
            'section'       => 'dialer_pushstat',
            'menu'          => 'lead',
            'start_date'    => $this->start_date,
            'end_date'      => $this->end_date,
            'leads'         => $leads,
            'users'         => $users,
            'username'      => $username,
            'name'          => $this->cre,
            'limit'         => $this->limit,
            'i'             => 1
        ); 
        return view('home')->with($data);
    }



    public function push($lead, $cre)
    {
        
        $emp = Employee::where('name','LIKE',$cre)->first();
        $dispo_remark = '';
        $source = '';
        $lead_status = '';
        $push_stat = [];
        if($lead->cdisposition_id)
        {
        $dispos = Disposition::find($lead->cdisposition_id);
        //dd($dispos);
        $dispo_remark = $dispos->disposition;
        }
        //dd($lead);
        if($emp) {
            $output = 'False';
            $push = 0;

            //echo $emp->user->username;
           //$url = DIALER_URI."?do=manualUpload&username=admin&password=contaquenv&campname=Sales_Outbound&skillname=ENGLISH&listname=sales01022016&phone1=".$phone."&agentname=".$cre;
             


             if(!$lead->dnc && !$lead->dialer_push_date)
                {
                    $push = 1;
                    if($lead->patient)
                        if(!$lead->patient->hasTag('VIP'))
                              $push = 1;
                            else 
                                $push = 0;
                            
                }
              else
                    $push = 0; 


                if($lead->cdisposition_id && $lead->cdisposition_id =='10')
                    $push = 0;
            }
        $source = $lead->lsource ? $lead->lsource->source_name : '';

        if($lead->status)
            $lead_status = $lead->status->name;
        if($push == 1)
        $push_stat = array('cre_name' => $lead->cre_name,'lead_status' => $lead_status, 'source' => $source, 'cre_assign_date' => $lead->cre_assign_date, 'push' => $push, 'lead_id'=>$lead->id,'phone'=>$lead->phone, 'lead_name' => $lead->name, 'username' =>$emp->user->username, 'output' => $output, 'dispo_date' => $lead->dispo_date, 'dispo_remark' => $dispo_remark, 'callback' => $lead->callback);
            return $push_stat;
    }

    public function execute(Request $request)
    {   
        $lead_ids =  $request->id;
        $list_id = $this->list_id;
        $username =  $request->username;
        //$emp = Employee::where('name','LIKE',$this->cre)->first();

        $i = 0;
        echo "<div style='text-align:left;height: 500px;width: 550px;overflow: auto'>";
        foreach($lead_ids as $lead_id)
        {
            $output= 'false2';
            $phone = $request->phone[$i];

            $phone = Helper::properMobile($phone);
            $pin1 = substr(trim($phone), 0, 2);
            $pin2 = substr(trim($phone), 0, 3);
            
              
            $cre_name = $request->cre_name[$i];
         
             //dd($username);
            //$cre_name = $request->cre_name[$i];
            //$dispo_date = $request->dispo_date[$i];
            //$dispo_remark = $request->dispo_remark[$i];
            //$callback = $request->callback[$i];
            //$username = $request->username[$i];
            //$push = $request->push[$i];
            //$cre_assign_date = $request->cre_assign_date[$i];
            //$lead_name = $request->lead_name[$i];
            //$lead_status = $request->lead_status[$i];
            //$source = $request->source[$i];
            
           
            //if($push==1)
            //{
            $ch = curl_init("http://192.168.1.203/test.ajax");
            $encoded_params = "do=manualUpload&username=admin&password=contaquenv&campname=Sales_Outbound&skillname=ENGLISH&listname=$list_id&phone1=".$phone;
            
            /*curl_setopt($ch, CURLOPT_POSTFIELDS,  $encoded_params);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($ch);
            curl_close($ch);*/
          if($pin1 != "11" && $pin2 != "011")
            $output = file_get_contents("http://192.168.1.203/test.ajax?".$encoded_params);
          else
            $output = "Landline-No. Skipped";
            
            
            //}
            if($output == "Number added successfully.")
            {
                //$lead = Lead::find($lead_id);
                $dialer_push = New DialerPush;
                $dialer_push->lead_id = $lead_id;
                //$dialer_push->user = $username;
                //$dialer_push->name = $cre_name;
               
                $dialer_push->phone = $phone;
                $dialer_push->list_id =  $list_id;
                $dialer_push->created_by = Auth::user()->employee->name;
                $dialer_push->status = $output;
                $dialer_push->save();
            }
            
            /*$push_stat = array('cre_name' => $lead->cre_name,'lead_status' => $lead_status, 'source' => $source, 'cre_assign_date' => $cre_assign_date, 'push' => $push, 'lead_id'=>$lead_id,'phone'=>$phone, 'lead_name' => $lead_name, 'username' =>$username, 'output' => $output, 'dispo_date' => $dispo_date, 'dispo_remark' => $dispo_remark, 'callback' => $callback);
            $push_stats[] = $push_stat;*/

            echo "<p><b>" . $lead_id . "</b>: Push Status <b>" . $output . "</b></p>";
            $i++;
            
        }
 
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function patientFeeStatus()
    {
        $patients = Patient::with('fee')->select('patient_details.*')
                    ->whereBetween('entry_date', array($this->start_date, $this->end_date))
                    ->get();
        //dd($patients );
       /* foreach($patients as $patient)
            {
                if($patient->fee)
                    echo "True=>".$patient->fee."<br><br>";
                else
                    echo "False"."<br><br>";
            }*/
             $data = array(
            'menu'          => 'service',
            'section'       => 'patient_feestatus',
            'start_date'    => $this->start_date,
            'end_date'      => $this->end_date,
            'patients'    => $patients,
            'i'             => 1
        );
        return view('home')->with($data);

    }



     public function sourceLeads($source_id, Request $request)
    {
        $users = User::getUsersByRole('cre');
        $statuses = Status::get();
        $dispositions = Disposition::get();
        $cre  = $request->user;
        $status_id  = $request->status;
        $disposition_id  = $request->disposition;
       
        $leads_query = Lead::with('sources.master', 'status')
                    
                        /*->join(DB::raw("(SELECT * FROM lead_source WHERE source_id = '$source_id') AS ls1"), function($join) {
                                 $join->on('marketing_details.id', '=', 'ls1.lead_id');
                            })*/
                     

                         /*->join(DB::raw("(SELECT * FROM lead_sources ls1 WHERE source_id = '$source_id' and id = (SELECT MAX(id) FROM lead_sources ls2 WHERE ls1.lead_id=ls2.lead_id)) AS ls"), function($join) {
                                 $join->on('marketing_details.id', '=', 'ls.lead_id');
                            });*/
                         ->whereHas('source', function($q) use($source_id) {
                                // Query the department_id field in status table
                                 $q->where('source_id', '=', $source_id); // '=' is optional
                                });

                          if(!empty($disposition_id))
                                $leads_query->join(DB::raw("(SELECT * FROM call_dispositions cd1 WHERE  disposition_id ='1' and id = (SELECT MAX(id) FROM call_dispositions cd2 WHERE cd1.lead_id=cd2.lead_id)) AS d"), function($join) {
                                     $join->on('marketing_details.id', '=', 'd.lead_id');
                                         
                                });
                            
                          if(!empty($cre))
                                $leads_query->join(DB::raw("(SELECT * FROM lead_cre A WHERE (cre = '$cre') and (deleted_at IS NULL OR deleted_at = '') and (created_at BETWEEN '$this->start_date' and '$this->end_date') and id = (SELECT MAX(id) FROM lead_cre B WHERE A.lead_id=B.lead_id)) AS c"), function($join) {
                                     $join->on('marketing_details.id', '=', 'c.lead_id');
                                });
                          else
                                $leads_query->join(DB::raw("(SELECT * FROM lead_cre A WHERE (deleted_at IS NULL OR deleted_at = '') and (created_at BETWEEN '$this->start_date' and '$this->end_date') and id = (SELECT MAX(id) FROM lead_cre B WHERE A.lead_id=B.lead_id)) AS c"), function($join) {
                                     $join->on('marketing_details.id', '=', 'c.lead_id');
                                });

                    


                    if(!empty($status_id))
                    
                        $leads_query->where('status_id', '=', $status_id);
                    else
                        $leads_query->where('status_id', '<>', '6');

                    $leads_query->orderBy('c.created_at', 'DESC');

                    $leads_query->SELECT('marketing_details.*');

        $leads =  $leads_query->limit($this->limit)->get();
              //dd($leads);      

        $data = array(
            'menu'          =>  'reports',
            'section'       =>  'source_leads',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'users'         =>  $users,
            'name'          =>  $cre,
            'statuses'      =>  $statuses,
            'status_id'     =>  $status_id,
            'dispositions'  =>  $dispositions,
            'disposition_id'=>  $disposition_id,
            'leads'         =>  $leads,
            'limit'         =>  $this->limit
        );

        return view('home')->with($data);
    }

    public function selfAssignCount(Request $request)
    {
        // select count(*) from dialer_push where created_at >='2016-03-02 00:00:00'
       /* $leads = LeadCre::with('lead')->join(DB::raw("(SELECT * FROM dialer_push A) AS c"), function($join) {
                                 $join->on('lead_cre.lead_id', '=', 'c.lead_id');
                            })
                        ->join(DB::raw("(SELECT * FROM call_dispositions cd1 WHERE  (created_at  BETWEEN '2016-03-04 00:00:00' and '2016-03-04 23:59:59') and id = (SELECT MAX(id) FROM call_dispositions cd2 WHERE cd1.lead_id=cd2.lead_id)) AS d"), function($join) {
                                 $join->on('lead_cre.lead_id', '=', 'd.lead_id');
                            })
        ->whereBetween('lead_cre.created_at',array('2016-03-04 00:00:00','2016-03-04 23:59:59'))->orderBy('lead_cre.cre','desc')->get();
*/
        /*$leads = LeadCre::with('lead')
                        ->where('self_assign','=',1)
                        ->whereBetween('lead_cre.created_at',array('2016-03-05 00:00:00','2016-03-05 23:59:59'))->orderBy('lead_cre.cre','desc')->get();
       */

         $leads =         Lead::with('cre', 'disposition')
                                ->join(DB::raw("(SELECT * FROM dialer_push A) AS c"), function($join) {
                                 $join->on('marketing_details.id', '=', 'c.lead_id');
                            })
                            ->whereHas('cre', function($q) {
                                // Query the department_id field in status table
                                 $q->whereBetween('lead_cre.created_at',array($this->start_date,$this->end_date)) // '=' is optiona
                                 ->whereRaw('lead_cre.cre = lead_cre.created_by');
                                })->get();
       // dd($leads);
        $leads = LeadCre::with('lead', 'lead.cre', 'lead.disposition')
                        ->join(DB::raw("(SELECT * FROM dialer_push A) AS c"), function($join) {
                                 $join->on('lead_cre.lead_id', '=', 'c.lead_id');
                            })
                       /* ->whereHas('lead.disposition', function($q) {
                                // Query the department_id field in status table
                                 $q->where('call_dispositions.created_at', '<', 'lead_cre.created_at'); // '=' is optional
                                })*/
                        ->whereRaw('lead_cre.cre = lead_cre.created_by')
                        ->whereBetween('lead_cre.created_at',array($this->start_date,$this->end_date))->orderBy('lead_cre.cre','desc')
                        ->get();

        $leads_in_dialer = DialerPush::whereBetween('created_at',array($this->start_date,$this->end_date))->count();
       
        $converted = Lead::with('cre', 'disposition')
                                ->join(DB::raw("(SELECT * FROM dialer_push A where created_at between '$this->start_date' and '$this->end_date') AS c"), function($join) {
                                 $join->on('marketing_details.id', '=', 'c.lead_id');
                            })
                               /* ->whereHas('cre', function($q) {
                                // Query the department_id field in status table
                                 $q->whereBetween('lead_cre.created_at',array($this->start_date,$this->end_date)) // '=' is optiona
                                 ->whereRaw('lead_cre.cre = lead_cre.created_by');
                                })*/
                                ->Join('patient_details as p', 'p.lead_id', '=', 'marketing_details.id')
                                ->join(DB::raw("(SELECT * FROM fees_details fd1 WHERE id = (SELECT min(id) FROM fees_details fd2 WHERE fd1.patient_id=fd2.patient_id)) AS fd"), function($join) {
                                     $join->on('p.id', '=', 'fd.patient_id');
                                    })
                                ->where('fd.created_at', '>', $this->start_date)
                                ->count();
                                //dd($converted);
                                //->whereBetween('c.created_at',array($this->start_date,$this->end_date))
                       

    /*    $total_leads = Lead::join(DB::raw("(SELECT * FROM dialer_push A) AS c"), function($join) {
                                 $join->on('marketing_details.id', '=', 'c.lead_id');
                            })
                        ->join(DB::raw("(SELECT * FROM call_dispositions cd1 WHERE (disposition_id  NOT IN(3,4,5,6,7)) and (created_at  BETWEEN '2016-03-04 00:00:00' and '2016-03-04 23:59:59') group by lead_id) AS d"), function($join) {
                                 $join->on('marketing_details.id', '=', 'd.lead_id');
                            })
                        ->count();*/
                        
        $data = array(
            'menu'          =>  'reports',
            'section'       =>  'self_assign',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'leads'         =>  $leads,
            'leads_in_dialer' => $leads_in_dialer,
            'converted'      => $converted
        );

        return view('home')->with($data);
    }


     
}