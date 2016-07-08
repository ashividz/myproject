<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Cart;
use App\Models\CartPayment;
use App\Models\CartStatus;
use App\Models\CartState;
use App\Models\Discount;
use App\Models\CartStep;
use App\Models\Patient;
use App\Models\Order;
use App\Models\OrderPatient;
use App\Models\Days365;
use App\Support\Helper;
use Redirect;
use Auth;
use Carbon;
use DB;

class CartReportController extends Controller
{
    protected $daterange;
    protected $start_date;
    protected $end_date;

    public function __construct(Request $request) 
    {
        $this->menu = 'cart.reports';
        //$this->user = isset($request->user) ? $request->user : Auth::user()->employee->name;
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y-m-d 0:0:0', strtotime($this->daterange[0])) : date("Y-m-d 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y-m-d 23:59:59', strtotime($this->daterange[1])) : date('Y-m-d 23:59:59');
    }

    public function index()
    {
        $data = array(
            'menu'          =>  'cart',
            'section'       =>  'reports.index'
        );    

        return view('home')->with($data);
    }

    /*public function cartStatusReport()
    {
        $carts = Cart::with('payments.method', 'steps', 'cre.employee.sup', 'step', 'shippings')          
                    ->whereBetween('created_at', array($this->start_date, $this->end_date))
                    ->orderBy('id', 'desc')
                    ->get(); //dd($carts);

        $statuses = CartStatus::get();

        $data = array(
            'menu'          =>  $this->menu,
            'section'       =>  'cartStatusReport',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'carts'         =>  $carts,
            'statuses'      =>  $statuses,
            'i'             =>  1
        );    

        return view('home')->with($data);
    }
    */

    /*public function goods()
    {
        $carts = Cart::with('payments.method', 'steps', 'cre.employee.sup', 'step', 'shippings', 'products') 
                    ->whereHas('products.category', function($query){
                        $query->whereIn('id', [2,4]);
                    })         
                    ->whereBetween('created_at', array($this->start_date, $this->end_date))
                    ->orderBy('id', 'desc')
                    ->get(); //dd($carts);

        $statuses = CartStatus::get();

        $data = array(
            'menu'          =>  $this->menu,
            'section'       =>  'goods',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'carts'         =>  $carts,
            'statuses'      =>  $statuses,
            'i'             =>  1
        );    

        return view('home')->with($data);
    }
    */

    public function showBalancePayments()
    {
        $data = array(
            'menu'          =>  'reports',
            'section'       =>  'sales.balance_payments'
        );    

        return view('home')->with($data);
    }

    public function getBalancePayments()
    {
        $carts = Cart::with('lead.patient.fees', 'cre.employee.supervisor.employee', 'currency', 'status', 'state', 'products', 'payments.method', 'source')
                ->where('status_id', '4')
                ->where('state_id', '3')
                ->whereRaw('amount - payment > 0')
                //->whereNull('balance_adjusted_by')
                //->orderBy('id', 'desc')
                ->get();

        return $carts;
    }

    public function getSales(Request $request)
    {
        $this->start_date = $request->start_date ? : Carbon::now()->subDays(30);
        $this->end_date = $request->end_date ? : Carbon::now();

         $limit = Carbon::parse($this->start_date)->diff(Carbon::parse($this->end_date))->days; 

        //$days = Days365::limit($limit)->get();
        $series = array();
        $inr = array();
        $usd = array();
        $date = array();

        //foreach ($days as $day) {
        for ($i=0; $i <= $limit; $i++) { 
           
            $dt = Carbon::parse($this->start_date)->addDays($i)->format('Y-m-d');
            
            /*$amountINR = CartPayment::whereHas('cart', function($q) {
                                $q->where('status_id', 4)
                                ->where('currency_id', 1);
                            })
                            ->where('date', $dt)
                            ->sum('amount');*/
            $amountINR =  Cart::
                            join('cart_payments as p', 'p.cart_id', '=', 'carts.id')
                            ->where('status_id', 4)
                            ->where('currency_id', 1)
                            ->where('date', $dt)
                            ->sum('p.amount');

            $amountUSD = Cart::
                            join('cart_payments as p', 'p.cart_id', '=', 'carts.id')
                            ->where('status_id', 4)
                            ->where('currency_id', 2)
                            ->where('date', $dt)
                            ->sum('p.amount');

            array_push($inr, (int) $amountINR);
            array_push($usd, (int) $amountUSD);
            array_push($date, Carbon::parse($dt)->format('D, jS M'));
        }
        $series['date'] =  $date;   
        $series['inr'] =  $inr;  
        $series['usd'] =  $usd;
        return $series;
    }

    public function funnel(Request $request)
    {
        $start_date = $request->start_date ? : Carbon::now()->subDays(30)->format('Y-m-d');
        $end_date = $request->end_date ? : Carbon::now()->format('Y-m-d');

        $statuses = CartStatus::get();
        $states = CartState::get();
        $funnels = array();
        $name = array();
        $count = array();

        

        foreach ($statuses as $status) {
            foreach ($states as $state) {
                $cart = Cart::where('state_id', $state->id)
                    ->where('status_id', $status->id)
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->count();
                //echo $cart;
                if ($cart) {
                    //$funnels[$status->name."-".$state->name] = $cart;
                    $funnel = [
                        $status->name."-".$state->name => $cart
                    ];
                    array_push($name, $status->name."-".$state->name);
                    array_push($count, $cart);
                }                    
            }            
        }

        $carts =  Cart::whereBetween('created_at', [$start_date, $end_date])->count();

        array_push($name, "Total Carts");
        array_push($count, $carts);

        $funnels['name'] = $name;
        $funnels['count'] = $count;
        return $funnels;
    }

    public function invoices(Request $request)
    {
        $start_date = $request->start_date ? : Carbon::now()->subDays(30)->format('Y-m-d');
        $end_date = $request->end_date ? : Carbon::now()->format('Y-m-d');

        $array = array();
        $name = array();
        $data = array();
        
        $carts = Cart::where('status_id', 4)
                    ->where('state_id', 1)
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->orWhere(function($q) use ($start_date, $end_date) {
                        $q->whereHas('payments', function($q) use ($start_date, $end_date) {
                            $q->whereIn('payment_method_id', [4,5]);
                        })
                        ->whereBetween('created_at', [$start_date, $end_date]);                        
                    })
                    //->has('invoices', '=', 0)
                    //->limit(10)
                    ->count();

        $invoices = Cart::with('invoices', 'payments')
                    ->where('status_id', 4)
                    ->where('state_id', 1)
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->orWhere(function($q) use ($start_date, $end_date) {
                        $q->whereHas('payments', function($q) {
                            $q->whereIn('payment_method_id', [4,5]);
                        })
                        ->whereBetween('created_at', [$start_date, $end_date]);                        
                    })
                    ->has('invoices', '>', 0)
                    //->limit(10)
                    ->count();


        array_push($array, [
            'name'  => 'Invoices',
            'y' =>   $invoices
        ]);


        array_push($array, [
            'name'  => 'NA',
            'y' =>   $carts - $invoices
        ]);

        return $array;
    }

    public function proformas(Request $request)
    {
        $start_date = $request->start_date ? : Carbon::now()->subDays(30)->format('Y-m-d');
        $end_date = $request->end_date ? : Carbon::now()->format('Y-m-d');

        $array = array();
        $name = array();
        $data = array();
        
        $carts = Cart::whereHas('payments', function($q) {
                            $q->whereIn('payment_method_id', [2]);
                        })
                    ->where('status_id', '>=', 2)
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->count();

        $proformas = Cart::whereHas('payments', function($q) {
                            $q->whereIn('payment_method_id', [2]);
                        })
                    ->where('status_id', '>=', 2)
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->has('proforma', '>', 0)
                    ->count();


        array_push($array, [
            'name'  => 'Proformas',
            'y' =>   $proformas
        ]);


        array_push($array, [
            'name'  => 'NA',
            'y' =>   $carts - $proformas
        ]);

        return $array;
    }

    public function shippings(Request $request)
    {
        $start_date = $request->start_date ? : Carbon::now()->subDays(30)->format('Y-m-d');
        $end_date = $request->end_date ? : Carbon::now()->format('Y-m-d');

        $array = array();
        $name = array();
        $data = array();
        
        $carts = Cart::whereHas('payments', function($q) {
                            $q->whereIn('payment_method_id', [4,5]);
                        })
                    ->where('status_id', '>=', 2)
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->count();

        $shippings = Cart::whereHas('payments', function($q) {
                            $q->whereIn('payment_method_id', [4,5]);
                        })
                    ->where('status_id', '>=', 2)
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->has('shippings', '>', 0)
                    ->count();


        array_push($array, [
            'name'  => 'Shippings',
            'y' =>   $shippings
        ]);


        array_push($array, [
            'name'  => 'NA',
            'y' =>   $carts - $shippings
        ]);

        return $array;
    }
}
