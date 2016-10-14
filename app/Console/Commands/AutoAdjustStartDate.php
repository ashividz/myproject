<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Patient;
use App\Models\Fee;
use App\Models\Diet;
use App\Models\Log;

use DB;
use Carbon\Carbon;

class AutoAdjustStartDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fee:adjuststartdate';
    protected $allowedExtension;
    protected $totalChanges;
    protected $minStartDate;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adjust the start date fee of all patients';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->allowedExtension = collect([
            (object) ['limit' => 0, 'extension' => 90],
            (object) ['limit' => 30,'extension' => 180],
        ]);
        $this->totalChanges = 0;
        $this->minStartDate = Carbon::today()->subDays(30);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today     = Carbon::today();
        $tomorrow  = Carbon::tomorrow();       
        $totalChanges = 0;
        
        $patients =  Patient::whereHas('fee', function($query) {
                        $query->where( 'end_date', '>=', DB::raw('CURDATE()'))
                              ->where( 'start_date', '>=', $this->minStartDate);
                    })->get();
        
        echo PHP_EOL.'patient ids:'.PHP_EOL;
        
        foreach ($patients as $patient) {
            if ($patient->cfee) {
                if ( $patient->cfee->start_date->lt($this->minStartDate) )
                    continue;
                
                $diet = Diet::where('date_assign','>=',$patient->cfee->start_date)
                        ->where('patient_id',$patient->id) 
                        ->whereRaw('IFNULL(diets.email,0) = 1')
                        ->orderBy('date_assign')
                        ->first();
                
                $duration  = $patient->cfee->duration ? $patient->cfee->duration : $patient->cfee->valid_months*30;
                
                //check if diet was not started on start date
                if ( (!$diet && $patient->cfee->end_date->eq($today) ) || ( $diet && Carbon::parse($diet->date_assign)->gt($patient->cfee->start_date)) ) {                
                    $start_date = null;
                    if(!$diet)
                        $start_date        = $tomorrow;
                    else
                        $start_date        = Carbon::parse($diet->date_assign);                    
                    $adjustDays        = $start_date->diffInDays($patient->cfee->start_date);
                    $daysFromEntryDate = $start_date->diffInDays($patient->cfee->entry_date);
                    $allowedExtension  = $this->getAllowedExtension($duration);
                    
                    //remove manual adjust cases
                    $diffInDays = $patient->cfee->start_date->diffInDays($patient->cfee->end_date);

                    if ( ($daysFromEntryDate <= $allowedExtension) && ($diffInDays <= $duration) ) {
                        $this->adjustStartDate($patient,$adjustDays);
                        echo $patient->id.PHP_EOL;     
                    }                    
                } 
            }
        }

        echo 'total changes : '.$this->totalChanges;
    }

    private function getAllowedExtension($duration)
    {
        if($duration<=0)
            return 0;
        return $this->allowedExtension->filter(function ($item) use ($duration) {
                return $item->limit < $duration;
           })->sortByDesc('limit')->first()->extension;                            
    }

    private function adjustStartDate($patient,$adjustDays)
    {
       $fees = $patient->fees()
                ->where('end_date','>=',$patient->cfee->end_date)
                ->get();
        foreach ($fees as $fee ) {            
            $log  = $this->getLogWithOldValue($fee);            

            $fee->start_date = $fee->start_date->addDays($adjustDays);
            $fee->end_date   = $fee->end_date->addDays($adjustDays);
            $fee->save();
            $this->totalChanges++;
            $log  = $this->updateLogWithNewValue($log,$fee);
            $log->save();            
        }                        
        return true;
    }

    private function getLogWithOldValue($fee)
    {
        $host = gethostname();
        $ip   = gethostbyname($host);

        $oldValue = array();
        $oldValue['start_date']   = $fee->start_date->toDateString();
        $oldValue['end_date']     = $fee->end_date->toDateString();
        $oldValue['created_at']   = $fee->created_at->toDateTimeString();
        $oldValue['updated_at']   = $fee->updated_at->toDateTimeString();
        $oldValue                 =  json_encode($oldValue);
            

        $log =  new Log;
        $log->user_id = 0;
        $log->owner_type = 'App\Models\Fee';
        $log->owner_id   = $fee->id;
        $log->old_value  = $oldValue;
        $log->type       = 'updated';
        $log->route      = 'Job:AutoAdjustStartDate';
        $log->ip         = $ip;
        return $log;
    }

    private function updateLogWithNewValue($log,$fee)
    {
        $newValue = array();
        $newValue['start_date']   = $fee->start_date->toDateString();
        $newValue['end_date']     = $fee->end_date->toDateString();
        $newValue['created_at']   = $fee->created_at->toDateTimeString();
        $newValue['updated_at']   = $fee->updated_at->toDateTimeString();;
        $newValue                 = json_encode($newValue);
        $log->new_value           = $newValue;
        return $log;
    }

}