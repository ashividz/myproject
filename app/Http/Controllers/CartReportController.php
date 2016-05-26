<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Cart;
use App\Models\CartStatus;
use App\Models\Discount;
use App\Models\CartStep;
use App\Models\Patient;
use App\Models\Order;
use App\Models\OrderPatient;
use App\Support\Helper;
use Redirect;
use Auth;

class CartReportController extends Controller
{
    protected $daterange;
    protected $start_date;
    protected $end_date;

    public function __construct(Request $request) 
    {
        $this->menu = 'cart.reports';
        $this->user = isset($request->user) ? $request->user : Auth::user()->employee->name;
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y-m-d 0:0:0', strtotime($this->daterange[0])) : date("Y-m-d 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y-m-d 23:59:59', strtotime($this->daterange[1])) : date('Y-m-d 23:59:59');
    }

    public function cartStatusReport()
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

    public function goods()
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
        $carts = Cart::with('lead.patient.fees', 'cre.employee.supervisor.employee', 'currency', 'status', 'state', 'products')
                ->where('status_id', '4')
                ->whereRaw('amount - payment > 0')
                //->orderBy('id', 'desc')
                ->get();

        return $carts;
    }
}
