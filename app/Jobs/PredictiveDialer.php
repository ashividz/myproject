<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Lead;
use App\Models\PredictiveJobRange;
use App\Models\DialerPushNew;
use App\Models\DialerPush;
use App\Models\PredictiveCount;
use App\Models\Patient;
use DB;
use Auth;
use Log;
use App\Support\Helper;

//define("DIALER_URI",  'http://192.168.1.203/test.ajax');

class PredictiveDialer extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $uid;
    protected $list_id;
    protected $dialerUserName;
    protected $dialerPassword;
    protected $campname;
    protected $skillname;
    protected $followUpDays;
    protected $onlyNotInt;
   // protected $rejoin;
    protected $new;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($uid, $list_id,$dialerUserName,$dialerPassword,$campname,$skillname,$followUpDays,$onlyNotInt,$new)
    {
       $this->uid             =   $uid;
       $this->list_id         =   $list_id;
       $this->dialerUserName  =   $dialerUserName;
       $this->dialerPassword  =   $dialerPassword;
       $this->campname        =   $campname;
       $this->skillname       =   $skillname;
       $this->followUpDays    =   $followUpDays;
       $this->onlyNotInt      =   $onlyNotInt;
       $this->new             =   $new;
      //sudo nohup php artisan queue:work --daemon --tries=3 --timeout=0
       //sudo nohup php artisan queue:listen --tries=3 --timeout=0
    }

    /**
     * Execute the job.
     *
     * @return void
     */


   public function handleNew()
    {
       Log::info("Job Started ". $this->uid);
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
            $dispo_date = date('2016-10-16 23:59:59');
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
               $predictiveJobRange->last_step_date = $end_limit;
               $predictiveJobRange->save();
        }
     }


  public function handle()
    {

      if($this->new)
      {
       $cre = ['Avadesh Kumar' , 'Shadhvi Srivastava' , 'Shivam Rohilla' , 'Manoj Kumar Rastogi' , 'Shashank Maheshwari' , 'Harshil Sharma' , 'Rohit Arora(NW580)'];
       Log::info("Job Started ". $this->uid);
       $leads = null;
       $predictiveJobRange = PredictiveJobRange::get()->first();
       $end_date    = $predictiveJobRange->end_date;
       $start_limit = $predictiveJobRange->last_step_date;
       $end_limit   = date('Y-m-d 23:59:59', strtotime('+9 days',strtotime($start_limit)));
       //$end_limit = date('Y-m-d 23:59:59',strtotime('+10 days',strtotime($start_limit)));
       $predictiveJobRange->last_step_date = $end_limit;
       $predictiveJobRange->save();
       $cur_date = 'Y-m-d';
       Log::info("Job range saved");
       // $dispo_date = date('2016-11-10 23:59:59');
       //$callback_date = date('2016-11-1009 23:59:59');
       //$dispo_date = $this->dispo_date;
       while($end_limit <= $end_date && $start_limit<=$end_date)
       {

         if(isset($start_limit) && $start_limit!="")
          {
             $leads = Lead::whereBetween('created_at',array($start_limit , $end_limit))
                    ->whereNotIn('cre_name', $cre)
                    ->where('source_id', '<>' , 10)
                    //->has('dispositions','<',4)
                    ->has('dnc', '<', 1)
                    ->whereRaw( "(country ='IN' or country is null or country ='')")
                    ->whereHas('dispositions',function($query) {
                        $query->whereNotNull('callback')
                        ->where('callback','>=',DB::raw('curdate()'));
                    },'<',1)
                    ->whereHas('dispositions',function($query) {
                        $query->where('created_at','>=',DB::RAW('DATE_ADD(CURDATE(), INTERVAL -'.$this->followUpDays.' DAY)'));
                    },'<',1)
                    //remove times jobs data
                    ->where('source_id','<>','57')
                    ->whereHas('status',function($query) {
                            $query->where('m_lead_status.id','<>',6);
                        })
                    //remove VIP Client
                    ->whereHas('patient',function($query) {
                        $query->whereHas('tags',function($q) {
                            $q->where('tags.id','=',9);;
                        });
                    },'=',0)
                    ->whereHas('source',function($query) {
                        $query->whereHas('master.channel',function($q) {
                            $q->where('channels.id','=',5);
                        });
                    },'=',0);

                    //include only not interested data if user has checked only not interested data
                    if ($this->onlyNotInt) {
                        $leads = $leads->whereHas('status',function($query) {
                            $query->where('m_lead_status.id','=',6);
                        });
                    }

                    $leads = $leads->doesnthave('patient')
                    ->get();
                $this->executex($leads);
          }

         $predictiveJobRange = PredictiveJobRange::get()->first();

         $start_limit = date('Y-m-d 0:0:0', strtotime('+1 days',strtotime($predictiveJobRange->last_step_date)));
         $end_limit   = date('Y-m-d 23:59:59', strtotime('+9 days',strtotime($start_limit)));
          if ($end_limit > $end_date)
              $end_limit = $end_date;
          $predictiveJobRange->last_step_date = $end_limit;
          $predictiveJobRange->save();
       }
      }
      else
      {
        Log::info("Job Started ". $this->uid);
       $leads = null;
       $predictiveJobRange = PredictiveJobRange::get()->first();
       $end_date    = $predictiveJobRange->end_date;
       $start_limit = $predictiveJobRange->last_step_date;
       $end_limit   = date('Y-m-d 23:59:59', strtotime('+9 days',strtotime($start_limit)));
       //$end_limit = date('Y-m-d 23:59:59',strtotime('+10 days',strtotime($start_limit)));
       $predictiveJobRange->last_step_date = $end_limit;
       $predictiveJobRange->save();
       $cur_date = 'Y-m-d';
       Log::info("Job range saved");
       // $dispo_date = date('2016-11-10 23:59:59');
       //$callback_date = date('2016-11-1009 23:59:59');
       //$dispo_date = $this->dispo_date;
       while($end_limit <= $end_date && $start_limit<=$end_date)
       {

         if(isset($start_limit) && $start_limit!="")
          {
              $patients = Patient::getRejoin($start_limit, $end_limit , $this->followUpDays);
              $this->regiondat($patients);

          }

         $predictiveJobRange = PredictiveJobRange::get()->first();

         $start_limit = date('Y-m-d 0:0:0', strtotime('+1 days',strtotime($predictiveJobRange->last_step_date)));
         $end_limit   = date('Y-m-d 23:59:59', strtotime('+9 days',strtotime($start_limit)));
          if ($end_limit > $end_date)
              $end_limit = $end_date;
          $predictiveJobRange->last_step_date = $end_limit;
          $predictiveJobRange->save();
        }
      }
    }


   public function handle2()
    {
       Log::info("Job Started ". $this->uid);
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
          $dispo_date = date('2016-10-16 23:59:59');
          $cur_date = date('Y-m-d 0:0:0');
          //dd( $this->end_date );
          $leads_qry = Lead::select('marketing_details.*')
              ->with('cre', 'disposition');
               /* ->with(['disposition' => function($q) use($dispo_date, $cur_date){
                      $q->whereIn('disposition_id',[2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14])
                       ->where('created_at', '<', $dispo_date)
                         ->where(function($r) use($cur_date) {
                                $r->whereRaw("callback < '$cur_date'")
                                ->orWhereNull('callback');
                              });
                  }]);*/

           $leads_qry->leftJoin(DB::raw("(SELECT * FROM call_dispositions cd1 WHERE id = (SELECT MAX(id) FROM call_dispositions cd2 WHERE cd1.lead_id=cd2.lead_id)) AS cd"), function($join) {
                               $join->on('marketing_details.id', '=', 'cd.lead_id');
                              })
                    ->where(function($a) use($cur_date, $dispo_date) {
                          $a->whereNull('cd.id')
                          ->orWhere(function($b) use($cur_date, $dispo_date) {
                                 $b->whereIn('cd.disposition_id', [2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14])
                                 ->where('cd.created_at', '<', $dispo_date)
                                 ->where(function($c) use($cur_date) {
                                        $c->whereRaw("cd.dispo_date < '$dispo_date'")
                                        ->orWhereNull('cd.callback');
                                      });
                                  });
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


              $leads_qry->leftJoin('lead_dncs as d', 'd.lead_id', '=', 'marketing_details.id');

              //$leads_qry->leftJoin('dialer_push as dp', 'dp.lead_id', '=', 'marketing_details.id');
              $leads_qry->whereBetween('marketing_details.created_at', array($start_limit, $end_limit))
                          //->whereNull('p.id')
                          ->whereNull('d.id')
                          //->where('marketing_details.phone_dnd', '=', '0')
                          /*->where(function($qe)  {
                                  $qe->where('marketing_details.phone_dnd', '=', '0')
                                     ->orWhereNull('marketing_details.phone_dnd');
                           })*/
                          //->whereNull('dp.id')
                          //->where('dp.list_id', '<>' , $this->list_id)
                          ->where(function($q)  {
                                  $q->where('marketing_details.country','=','IN')
                                    ->orWhereNull('marketing_details.country')
                                    ->orWhere('marketing_details.country','=', '');
                           });


              //->whereNotNull('marketing_details.source_id')


               $leads = $leads_qry->orderBy('marketing_details.created_at')->get();

              /* $PredictiveCount = PredictiveCount::get()->first();
               $PredictiveCount->rows = $PredictiveCount->rows + $leads;
               $PredictiveCount->last_lead_date = $predictiveJobRange->last_step_date;
               $PredictiveCount->save(); */

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
            $encoded_params = "do=manualUpload&username=".$this->dialerUserName."&password=".$this->dialerPassword."&campname=".$this->campname."&skillname=".$this->skillname."&listname=".$this->list_id."&phone1=".$phone;

            /*curl_setopt($ch, CURLOPT_POSTFIELDS,  $encoded_params);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($ch);
            curl_close($ch);*/
            //}

          if($pin1 != "11" && $pin2 != "011")
            $output = file_get_contents("http://192.168.1.200/test.ajax?".$encoded_params);
          else
            $output = "Landline-No. Skipped";

            //$output = "Number added successfully.";

            if($output)
            {
                //$lead = Lead::find($lead_id);
                $dialer_push = New DialerPush;
                $dialer_push->lead_id = $lead_id;
                //$dialer_push->user = $username;
                //$dialer_push->name = $cre_name;

                $dialer_push->phone = $phone;
                $dialer_push->list_id =  1;
                $dialer_push->created_by = $this->uid;
                $dialer_push->lead_date = $lead_date;
                $dialer_push->status = $output;
                $dialer_push->save();
            }
      }
    }
    public function regiondat($patients)
    {
        foreach($patients as $patient)
        {
          if($patient->lead)
          {
            $output= 'false2';
            $lead_id = $patient->lead->id;
            $phone = $patient->lead->phone;
            $lead_date = $patient->lead->created_at;
            $phone = Helper::properMobile($phone);
            $pin1 = substr(trim($phone), 0, 2);
            $pin2 = substr(trim($phone), 0, 3);

            if($patient->lead->cre)
            $cre_name = $patient->lead->cre->cre;

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
            $encoded_params = "do=manualUpload&username=".$this->dialerUserName."&password=".$this->dialerPassword."&campname=".$this->campname."&skillname=".$this->skillname."&listname=".$this->list_id."&phone1=".$phone;



            /*curl_setopt($ch, CURLOPT_POSTFIELDS,  $encoded_params);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($ch);
            curl_close($ch);*/
            //}



            if($pin1 != "11" && $pin2 != "011")
              $output = file_get_contents("http://192.168.1.200/test.ajax?".$encoded_params);
            else
            $output = "Landline-No. Skipped";

            //$output = "Number added successfully.";
            //dd($output);
            if($output)
            {
                //$lead = Lead::find($lead_id);
                $dialer_push = New DialerPush;
                $dialer_push->lead_id = $lead_id;
                //$dialer_push->user = $username;
                //$dialer_push->name = $cre_name;

                $dialer_push->phone = $phone;
                $dialer_push->list_id =  2;
                $dialer_push->created_by = $this->uid;
                $dialer_push->lead_date = $lead_date;
                $dialer_push->status = $output;
                $dialer_push->save();
            }
        }   }
    }
}
