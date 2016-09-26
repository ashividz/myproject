<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Lead;

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
    
}
