<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Lead;

use App\Models\DndJobRange;
use DB;
use Auth;
use Log;
use App\Support\Helper;

//define("DIALER_URI",  'http://192.168.1.203/test.ajax');

class DndCheck extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $uid;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($uid)
    {    
       $this->uid = $uid;
       
        //sudo nohup php artisan queue:work --daemon --tries=3 --timeout=0
       //sudo nohup php artisan queue:listen --tries=3 --timeout=0
    }

    /**
     * Execute the job.
     *
     * @return void
     */

  

   public function handle() 
    {
       Log::info("Job Started ");
       $leads = null;
       $dndJobRange = DndJobRange::get()->first();
       $totalRecords = Lead::where(function($q)  {
                                $q->where('country','=','IN')
                                  ->orWhereNull('country')
                                  ->orWhere('country','=', '');
                                })
                            ->has('dnd', '<', 1)->count();
       $count = $totalRecords - $dndJobRange->last_step;
       $start_limit = $dndJobRange->last_step;
       
       $dndJobRange->total =  $totalRecords;
       $dndJobRange->save();
       Log::info("Job range saved ".$dndJobRange->last_step);
       
 
       $leads = Lead::where(function($q)  {
                              $q->where('country','=','IN')
                                ->orWhereNull('country')
                                ->orWhere('country','=', '');
                              })
                       ->has('dnd', '<', 1)
                       ->skip($start_limit)
                       ->take($count)
                       ->orderBy('id')
                       ->limit(500)->get();
       
       $i = 1;
      
        foreach($leads as $lead)
         { 
            
           Lead::checkDND($lead);
           $lead->save();
          // Log::info($lead->id."/ ".$lead->phone."/ ".$lead->mobile);
           if($i%20 == 0)
            {
              $dndJobRange->last_step = $i;
              $dndJobRange->save();
            }
           $i++;
         }
         $dndJobRange->last_step = $i-1;
         $dndJobRange->save();
    }

     public function failed()
    {
         Log::info("Job not started ");
    }

    
}
