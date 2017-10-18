<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Email;
use App\Models\Lead;
use App\Models\Product;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\SendProductReminderEmail;
use Carbon\Carbon;

use DB;
use Auth;

class ProductReminderMail extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature         = 'product_reminder:send';
    protected $productCategoryId = 2;
    protected $reorderDuration   = 25;
    protected $emailDuration     = 5;
    protected $bfaProductId      = 40;
    protected $emailTemplateId   = 25;
    protected $productKitId      = 8;
    protected $herbs;
    protected $herbIDs           = [9,10,11,12,13,14];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sends a product purchase reminder to client';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->herbs = Product::whereIn('id',$this->herbIDs)->get();
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
        foreach ($leads as $lead) {                        
            $lastEmail =  Email::where('template_id',$this->emailTemplateId)
                        ->where('lead_id',$lead->id)
                        ->where('created_at','>=',$lead->lastCart->updated_at)
                        ->orderBy('created_at','desc')
                        ->first();
                if ( !$lastEmail || ( $lastEmail && Carbon::parse($lastEmail->created_at)->addDays($this->emailDuration)->lte(Carbon::today()) ) ) {                
                    $this->dispatch(new SendProductReminderEmail($lead,$lead->lastCart->products,$this->emailTemplateId,$this->productKitId,$this->herbs));
                    $queuedLeadCount++;
                }
        }
        echo 'Total leads without product reorder :'.$leads->count().PHP_EOL;
        echo 'Total leads queued :'.$queuedLeadCount.PHP_EOL;

    }

    private function getLeads()
    {
        $lastOrderDate    =  Carbon::today()->subDays($this->reorderDuration);

        $leads = Lead::whereHas('carts',function($query) use ($lastOrderDate) {
                $query->where('carts.updated_at','<=',$lastOrderDate)
                  ->whereHas('products',function($q) {
                      $q->where('product_category_id',$this->productCategoryId)
                        ->where('products.id','<>',$this->bfaProductId);
                  });
            })
            ->whereHas('carts',function($query) use ($lastOrderDate) {
                $query->where('carts.updated_at','>',$lastOrderDate)
                  ->whereHas('products',function($q) {
                      $q->where('product_category_id',$this->productCategoryId)
                        ->where('products.id','<>',$this->bfaProductId);
                  });
            },0)
            /*->whereHas('carts.products',function($query) use ($lastOrderDate) {
                $query->where('product_category_id',$this->productCategoryId)
                  ->where('carts.updated_at','>',$lastOrderDate)
                  ->where('products.id','<>',$this->bfaProductId);
            },0)*/
        ->whereRaw('(country is null or country = "" or country="IN")')
        ->with([ 
            'carts' => function ($query) use ($lastOrderDate) {
                $query->whereHas('products',function($q) use($lastOrderDate) {
                    $q->where('product_category_id',$this->productCategoryId)
                    ->where('carts.updated_at','<=',$lastOrderDate)
                    ->where('products.id','<>',$this->bfaProductId);
                })
                ->with([
                    'products'=>function($qry) {
                    $qry->where('product_category_id',$this->productCategoryId)
                    ->where('products.id','<>',$this->bfaProductId);
                }]);
            }
        ])
        ->orderBy('id','desc')
        ->get();        

        foreach ($leads as $lead) {            
            $lead->lastCart = $lead->carts->sortByDesc('updated_at')->first();
        }

        return $leads;
    }

}