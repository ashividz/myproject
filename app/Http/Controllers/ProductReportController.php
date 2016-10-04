<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Cart;
use App\Models\Fee;
use App\Models\Lead;
use App\Models\Patient;

use DB;
use Carbon\Carbon;

class ProductReportController extends Controller
{
    public $start_date;
    public $end_date;    
    private $menu;
    private $productCategoryId;
    private $bfaProductId;
    private $reorderDuration;

    public function __construct(Request $request)
    {
    
        $this->menu       = 'marketing';
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y-m-d 0:0:0", strtotime("-30 days"));
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y-m-d 23:59:59');
        $this->productCategoryId = 2;
        $this->bfaProductId      = 40;
        $this->reorderDuration   = 25;
    }     

    public function products()
    {
        $data = array(
            'menu'    => $this->menu,
            'section' => 'reports.products.products'
        );

        return view('home')->with($data);
    }

    public function repeatOrders()
    {
        
    }

    public function noPurchases()
    {
        $data = array(
            'menu'    => $this->menu,
            'section' => 'reports.products.no_purchases'
        );
        return view('home')->with($data);
    }

    public function noRepeatPurchases()
    {   
        $leads = $this->getNoRepeatPurchases();
        $data = array(
            'menu'    => $this->menu,
            'section' => 'reports.products.no_repeat_purchases',
            'leads'   => $leads,
        );
        return view('home')->with($data);
    }

    public function getProducts(Request $request)
    {
        $categories = $request->categories;
        
        $leads = Lead::where(function($query) use ($categories) {            
            foreach ($categories as $category ) {
                $query = $query->whereHas('orders',function($q) use ($category) {
                    $q->where('orders.product_category_id',$category)
                      ->whereBetween('orders.updated_at',array($this->start_date,$this->end_date));
                });
            }
        });

        $leads = $leads->with([
            'orders'  => function($query) use ($categories) {
                $query->whereIn('orders.product_category_id',$categories)
                      ->whereBetween('orders.updated_at',array($this->start_date,$this->end_date))
                      ->with('cart');
            }])->get();

        foreach ($leads as $lead) {
            $lead->products = collect();            
            foreach ($lead->orders as $order){                
                $products = $order->cart->products()
                            ->where('products.product_category_id',$order->product_category_id)
                            ->whereBetween('products.updated_at',array($this->start_date,$this->end_date));
                foreach ($order->cart->products as $product) {                    
                    $product->purchase_date = $order->updated_at->toDateTimeString();
                    $product->cart_id = $order->cart->id;
                    $lead->products->push($product);     
                }
            }
        }

        return $leads;


    }

    public function getNoPurchases(Request $request)
    {
        $leadIds = Cart::whereHas('products',function($query) {
                $query->where('product_category_id',$this->productCategoryId)
                    ->where('products.id','<>',$this->bfaProductId);
                })->select('lead_id')->get()->toArray();
        

        $patients =   Patient::whereNotIn('lead_id',$leadIds)
                ->join(DB::RAW('(select *    from fees_details f1 where id = (select id from fees_details f2 where f1.patient_id=f2.patient_id order by end_date desc limit 1)) as f'),'f.patient_id','=','patient_details.id')
                ->whereHas('diets',function($query) {
                    $query->where('date_assign', '<=', DB::RAW('DATE_ADD(CURDATE(), INTERVAL -7 DAY)'))
                    ->where('date_assign','>=',DB::RAW('(ifnull((select start_date from fees_details where patient_id = patient_details.id order by end_date desc limit 1),"1970-01-01"))'))  ;
                })->whereHas('lead',function($query){
                    $query->whereRaw('(country is null or country="" or country="IN")');
        
                })
                ->with('lead')
                ->orderBy('end_date','desc')
                ->take($request->limit)
                ->skip($request->offset)
                ->get();
        return $patients;
    }

    public function getNoRepeatPurchases()
    {
        $lastOrderDate    =  Carbon::today()->subDays($this->reorderDuration);
        
        $leads = Lead::whereHas('carts.products',function($query) use ($lastOrderDate) {
                $query->where('product_category_id',$this->productCategoryId)
                  ->where('carts.updated_at','<=',$lastOrderDate)
                  ->where('products.id','<>',$this->bfaProductId);
            })
            ->whereHas('carts.products',function($query) use ($lastOrderDate) {
                $query->where('product_category_id',$this->productCategoryId)
                  ->where('carts.updated_at','>',$lastOrderDate)
                  ->where('products.id','<>',$this->bfaProductId);
            },0)
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
        ->get();        
        
        return $leads;

    }
    
}
