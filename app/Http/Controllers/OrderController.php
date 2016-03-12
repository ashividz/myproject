<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\ProductCategory;

class OrderController extends Controller
{
    protected $daterange;
    protected $start_date;
    protected $end_date;

    public function __construct(Request $request)
    {        
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y-m-d 0:0:0", strtotime("-45 days"));
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y-m-d 23:59:59');
        
    } 

    public function index(Request $request)
    {
        if ($request->category_id) {

            $orders = Order::where('product_category_id', '=', $request->category_id)->get();

        } else {

            $orders = Order::get();
        }

        $orders->load('category', 'cart.cre', 'cart.products', 'cart.currency');
        
        $categories = ProductCategory::get();

        $data = array(
            'menu'              =>  'cart',
            'section'           =>  'orders',
            'orders'            =>  $orders,
            'categories'        =>  $categories,
            'category_id'       =>  $request->category_id
        );
        return view('home')->with($data);

    }

    public function show($id)
    {
        $order = Order::find($id);

        $data = array(
            'menu'              =>  'cart',
            'section'           =>  'order',
            'order'             =>  $order
        );
        return view('home')->with($data);
    }
}