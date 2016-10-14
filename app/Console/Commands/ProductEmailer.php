<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Email;
use App\Models\Patient;
use App\Models\Lead;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\SendProductEmail;
use Carbon\Carbon;

use DB;
use Auth;

class ProductEmailer extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product_email:send';
    protected $productCategoryId = 2;
    protected $waitDaysAfterDiet = 7;
    protected $emailDuration     = 5;
    protected $bfaProductId      = 40;
    protected $emailTemplateId   = 26;
    protected $productKitId      = 8;
    protected $minEndDate;
    protected $herbs;
    protected $herbIDs           = [9,10,11,12,13,14];


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sends product emailers to patients who have not purchased the product';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->herbs = Product::whereIn('id',$this->herbIDs)->get();
        $this->minEndDate = Carbon::today()->subDays(60);
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $queuedLeadCount = 0;
        $leads = $this->getLeads();
        
        foreach ($leads as $l) {            
            $lead = Lead::find($l->id);
            $lastEmail =  Email::where('lead_id',$lead->id)
                            ->where('template_id',$this->emailTemplateId)
                            ->orderBy('created_at','desc')
                            ->first();
            if ( !$lastEmail || ( $lastEmail && Carbon::parse($lastEmail->created_at)->addDays($this->emailDuration)->lte(Carbon::today()) ) ) {                
                $this->dispatch(new SendProductEmail($lead,$this->emailTemplateId));
                $queuedLeadCount++;
            }
        }
        echo 'Total leads without product order :'.$leads->count().PHP_EOL;
        echo 'Total leads queued :'.$queuedLeadCount.PHP_EOL;

    }

    private function getLeads()
    {
        $patients = Patient::whereHas('fees',function($query){
                        $query->where('end_date','>=',$this->minEndDate);
                    })
                    ->whereHas('diets',function($query) {
                        $query->where('date_assign', '<=',Carbon::today()->subDays($this->waitDaysAfterDiet));
                    })
                    ->whereDoesNtHave('lead',function($query) {
                        $query->whereHas('carts.products' ,function($q) {
                            $q->where('product_category_id',$this->productCategoryId)
                                ->where('products.id','<>',$this->bfaProductId);
                        });
                    })
                    ->select('lead_id')
                    ->get();        
        
        $leads  = Lead::whereIn('id',$patients->pluck('lead_id')->toArray())
                    ->whereRaw('(country is null or country = "" or country="IN")')
                    ->orderBy('id','desc')
                    ->select('id')
                    ->get();
        return $leads;
    }

}
