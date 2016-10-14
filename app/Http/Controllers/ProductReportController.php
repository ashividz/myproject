<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Lead;
use App\Models\ProductCategory;
use App\Models\Cart;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductReportController extends Controller
{
    public $start_date;
    public $end_date;    
    private $menu;

    public function __construct(Request $request)
    {
    
        $this->menu       = 'marketing';
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y-m-d 0:0:0", strtotime("-30 days"));
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y-m-d 23:59:59');
        
    }     

    public function products()
    {
        $data = array(
            'menu'    => $this->menu,
            'section' => 'reports.products'
        );

        return view('home')->with($data);
    }

    public function repeatOrders()
    {
        
    }

    public function getProducts(Request $request)
    {
        $categories = $request->categories;

        $n_categories = ProductCategory::whereNotIn('id', $categories)->get();
        $n_categories = $n_categories->flatten()->pluck('id');

        $leads = Lead::with('carts.products.category', 'carts.payments.method')
                    ->whereHas('carts', function($q) use ($categories) {            
                        foreach ($categories as $category ) {
                            $q = $q->whereHas('products.category',function($q) use ($category) {
                                $q->where('id', $category);
                            });
                        }
                    });
        if (count($categories) == 1) {
            $leads = $leads->whereHas('carts', function($q) use ($categories) {            
                    foreach ($categories as $category ) {
                        $q = $q->whereHas('products.category',function($q) use ($category) {
                            $q->where('id', $category);
                        }, '<', 1);
                    }
                }, '<', 1);
        }
        
                    /*->where(function($q) use ($n_categories) {
                        foreach ($n_categories as $category ) {
                            $q = $q->whereHas('carts.products.category',function($q) use ($category) {
                                $q->where('id', $category);
                            }, '<', 1);
                        }
                    })*/
        $leads = $leads->with(['carts' => function($q) {
                        $q->where('status_id', '>=', 3)
                        ->whereNotIn('state_id', [2]);
                    }])
                    ->whereHas('carts', function($q) {
                        $q->where('status_id', '>=', 3)
                        ->whereNotIn('state_id', [2]);
                    })
                    ->whereHas('carts.payments', function($q) {
                        $q->whereBetween('updated_at', array($this->start_date,$this->end_date));
                    })
                    //->limit(20)
                    ->get();

        /*$carts = Cart::where(function($query) use ($categories, $n_categories) {
            foreach ($categories as $category) {
                $query = $query->whereHas('products',function($q) use ($category){
                    $q->where('product_category_id',$category);
                });                
            } 

            foreach ($n_categories as $category) {
                $query = $query->whereHas('products',function($q) use ($category){
                    $q->where('product_category_id',$category);
                }, 0);                
            }            
        })
        ->limit(100)
        ->get();

        dd($carts);*/
        
        /*$leads = Lead::where(function($query) use ($categories) {            
                    foreach ($categories as $category ) {
                        $query = $query->whereHas('carts.products.category',function($q) use ($category) {
                            $q->where('id', $category);
                        });
                    }
                })
                ->with(['carts' => function($q) {
                    $q->whereBetween('updated_at', array($this->start_date,$this->end_date))
                        ->where('status_id', '>=', 3)
                        ->whereNotIn('state_id', [2]);
                }])
                ->with('carts.products.category', 'carts.payments.method')
                
                ->get();*/

        /*$leads = $leads->with([
            'orders'  => function($query) use ($categories) {
                $query->whereIn('orders.product_category_id',$categories)
                      ->whereBetween('orders.updated_at',array($this->start_date,$this->end_date))
                      ->with('cart');
            }])->get();*/

        /*foreach ($leads as $lead) {
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
        }*/

        return $leads;


    }
    
}
