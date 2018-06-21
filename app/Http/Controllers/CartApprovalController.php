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
use App\Models\ApproverPayment;
use App\Models\ApproverDiscount;
use App\Models\User;
use App\Models\Lead;
use App\Models\CartProduct;


use App\Models\Email;
use App\Models\EmailTemplate;
use App\Support\Helper;

use Mail;

use Redirect;
use Auth;
use DB;

class CartApprovalController extends Controller
{
    protected $daterange;
    protected $start_date;
    protected $end_date;
    protected $template_1w ;
    protected $template_1 ;
    protected $template_2w ;
    protected $template_2 ;
    protected $template_3w ;
    protected $template_3 ;
    protected $template_6w ;
    protected $template_6 ;
    protected $template_12w ;
    protected $template_12 ;

     $list = array(
                    90 => 90,139 => 90,132 => 90,126 =>90,105 => 105,118 => 105,136 => 105,141 => 105,102 => 102,120 => 102,128 =>102,135 =>102,140 =>102,91  => 91 ,130 => 91,138 =>91,81  => 81,137 => 81,133 => 81,129 => 81,125 => 81,115 => 81,85 => 85,134 =>85,117 => 85,84 => 84,
                    131 => 84,116 => 84,109 => 109,123 => 109,110 => 110,124 => 110,95  => 95,127 => 95,119 => 95,
                 );

            

    public function __construct(Request $request)
    {
    
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y-m-d 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y-m-d 23:59:59');

        $this->template_1w = 86;
        $this->template_1 = 87;
        $this->template_2w = 88;
        $this->template_2 = 89;
        $this->template_3w = 90;
        $this->template_3 = 91;
        $this->template_6w = 92;
        $this->template_6 = 93;
        $this->template_12w = 94;
        $this->template_12 = 95;
       
    } 

    public function index()
    {
        return view('cart.approval');
    }

    public function show($pending = null)
    {
        $roles = Helper::roles(); //dd($roles);

        $users = [];

        if (Auth::user()->hasRole('service_tl') || 
            Auth::user()->hasRole('service')) {
            
            $users = User::getUsersByRole('nutritionist',Auth::id(),false);
            $users = $users->pluck('id');

        } elseif (Auth::user()->hasRole('sales_tl') || 
            Auth::user()->hasRole('sales')) {
            
            $users = User::getUsersByRole('cre',Auth::id(),false);
            $users = $users->pluck('id');
        }; 

        //dd($users);

        $query = Cart::with('payments.method', 'steps', 'cre.employee.sup', 'step')
                    ->whereHas('approvers', function($q) use ($roles) {
                        $q->whereIn('approver_role_id', $roles);
                    });
                                        
        if ($pending == 'pending') {
            $query->where('state_id','<>','3');
            if (Auth::user()->hasRole('finance')) {
                $query->where('state_id','=','1');
                $query->whereHas('payments', function($q) use ($roles) {
                    $q->where(function($q1){
                        $q1->where('payment_method_id','<>',2)
                        ->where('payment_method_id','<>',4)
                        ->where('payment_method_id','<>',5);                    
                    });
                });
            }
            if ( !isset($_POST['daterange']) ){
                $this->start_date = date('2016-04-01 00:00:00');
                $this->end_date   = date('Y-m-d 23:59:59');
            }                              
        } 
        if ($pending == 'cod') {
            $query->where('state_id','<>','3');
            if (Auth::user()->hasRole('finance')) {
                $query->where('state_id','=','1');
                $query->whereHas('payments', function($q) use ($roles) {
                    $q->where(function($q1){
                        $q1->where('payment_method_id','=',2)
                        ->orWhere('payment_method_id','=',4)
                        ->orWhere('payment_method_id','=',5);                    
                    });
                 });
            }
            if ( !isset($_POST['daterange']) ){
                $this->start_date = date('2016-04-01 00:00:00');
                $this->end_date   = date('Y-m-d 23:59:59');
            }                                                            
        }

        if ($pending == 'pending_registration') {
            $query->where('status_id','=','4');
            $query->where('state_id','<>','3');
            
            if ( !isset($_POST['daterange']) ){
                $this->start_date = date('2016-04-01 00:00:00');
                $this->end_date   = date('Y-m-d 23:59:59');
            }                                                            
        }                              
        
        $query->whereBetween('updated_at', array($this->start_date, $this->end_date));

        if($users) {
            $query = $query->whereIn('created_by', $users);
        }

        $carts = $query->orderBy('id', 'desc')->get(); //dd($carts);

        $statuses = CartStatus::get();

        $data = array(
            'menu'          =>  'cart',
            'section'       =>  'approval1',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'carts'         =>  $carts,
            'statuses'      =>  $statuses,
            'i'             =>  1
        );    

        return view('home')->with($data);
    }

    public function store(Request $request)
    {
        if ($request->get('state')) {
            foreach($request->get('state') as $id => $state_id)
            {
                $cart = Cart::find($id);
                $remark = $request->input('remark.'.$id);


                if ($state_id == 2) { //Rejected

                    CartStep::store($cart->id, $cart->status_id, $state_id, $remark);

                    Cart::updateState($cart->id, $state_id);

                } elseif ($state_id == 3) { //Approved

                    $count = CartStatus::count();

                    //Complete State for same step
                    CartStep::store($cart->id, $cart->status_id, $state_id, $remark);

                    if($cart->status_id < $count) {
                        //Create next CartStep
                        CartStep::nextStatus($id);
                        

                    }  else {                        
                        Cart::updateState($cart->id, $state_id); 
                    }    

                }  elseif ($state_id == 4) { //Multiple Discount Approvers
                    
                    //dd($request);

                    $step = CartStep::where('cart_id', $cart->id)->orderBy('id', 'desc')->first();
                    //$request->input('discount_id.'.$id); 
                    $discount_id = $step->discount_id + 1;
                    
                    //dd($discount_id);
                    $maxDiscount = max(array_pluck($cart->products, 'pivot.discount')); 
                    //dd($maxDiscount);

                    $discount = Discount::where('value', '<', $maxDiscount)
                                ->where('id', $discount_id)->first(); 
                                //dd($discount);

                    $state_id = $discount ? 4 : 3;

                    //Complete State for same step
                    CartStep::store($cart->id, $cart->status_id, $state_id, $remark, $discount_id); 

                    if(!$discount) {

                        //Create next CartStep
                        CartStep::nextStatus($id); 

                        Cart::updateState($cart->id, 1);        
                    }                 
                } 
            }

            $data = array(
                'message' => 'Process updated', 
                'status' => 'success'
            ); 

        } else if ($request->get('id')){

            $cart = Cart::find($request->get('id'));

            //Patient Registration
            $patient = Patient::register($cart);

            //if ($patient) {
            if ($cart->hasProductCategories([1])) {
                $cart->orders->store($patient);
            }
            
            //Order::store($cart, $patient);
            //}

            //Create Order
            //Order::store($cart);

            //Complete State for same step
            CartStep::store($cart->id, $cart->status_id, 3);

            $data = array(
                'message'       => 'Order Placed', 
                'status'        => 'success'
            );
        }

           

        return back()->with($data);

    }

    public function modal($id)
    {
        $cart  = Cart::find($id);

        $data = array(
             
            'cart'     => $cart
        );    

        return view('cart.modals.update')->with($data);
    }

    public function update(Request $request, $id)
    {
        $cart  = Cart::find($id);

        //Reset Cart Step
        CartStep::store($cart->id, $cart->status_id, 1, $request->remark); 
        Cart::updateState($cart->id, 1);

        $data = array(
            'message' => 'Process updated', 
            'status' => 'success'
        );   

        return back()->with($data);
    }

    public function canApprovePayment(Request $request)
    {
        $cart = Cart::find($request->cart_id);
        if (!$cart || $cart->products->isEmpty() || $cart->payments->isEmpty()) {
            return 'false';
        }
        if (Auth::user()->canApprovePayment($cart)) {
            return 'true';
        }

        /*if($approver) {
            return 'true';
        }*/

        return ['message' => 'Cannot approve payment method', 'status' => 'Error!'];;
    }

    public function canApproveDiscount(Request $request)
    {
        $cart = Cart::find($request->cart_id);
        if (!$cart) {
            return ['message' => 'Cart Not Found', 'status' => 'Error!'];
        }
        else if ($cart->products->isEmpty()) {
            return ['message' => 'No products in Cart', 'status' => 'Error!'];
        }
        else if ($cart->payments->isEmpty()) {
            return ['message' => 'No payment details in Cart', 'status' => 'Error!'];
        }
        //dd(Auth::user()->roles->pluck('id'));
        $discount = $cart->discountSteps();

        if (!$discount) {
            return 'true';
        }

        //dd($discount);

        /*$approver = ApproverDiscount::where('discount_id', $discount->id)
                        ->whereIn('approver_role_id', Auth::user()->roles->pluck('id'))
                        ->first();
                        //dd($approver);*/

        if(Auth::user()->canApproveDiscount($cart)) {
            return ['discount' => $discount->value, 'discount_id' => $discount->id ];
        }
        return 'false';
        //return ['message' => 'Cannot approve discount', 'status' => 'Error!'];
    }

    public function approve(Request $request)
    {
        $cart = Cart::find($request->cart_id);

        if (!$cart) {
            return ['message' => 'Cart Not Found', 'status' => 'Error!'];
        }

        $discount_id = $request->discount_id ? : null;

        if ($request->state == 1) {

            if($cart->status_id == 2) {

            	$this->inventryDatabase($cart);
                
                if (!Auth::user()->canApproveDiscount($cart)) {
                    abort('500', 'Cannot Approve Discount');
                }
                $discount = $cart->discountSteps();

                if ($discount) { //Set State = Progress
                    CartStep::store($request->cart_id, $cart->status_id, 4, $request->remark, $discount_id);
                    //Cart::updateState($cart->id, 4); 

                    $discount = $cart->discountSteps();
                    if (!$discount) {
                        CartStep::store($request->cart_id, $cart->status_id, 3, $request->remark, $discount_id);
                        CartStep::nextStatus($cart->id);
                    }
                } else {
                    CartStep::store($request->cart_id, $cart->status_id, 3, $request->remark, $discount_id);
                    CartStep::nextStatus($cart->id);
                }

                if(isset($request->benefitCart) && $request->benefitCart)
                {
                    
                    CartStep::store($request->cart_id, 3, 3, 'Reference Benefit', $discount_id);
                    CartStep::store($request->cart_id, 4, 3, 'Reference Benefit', $discount_id);
                    Cart::updateStatus($cart->id, 4); 
                    Cart::updateState($cart->id, 3); 
                    $cart = Cart::find($cart->id);

                    $patient = Patient::register($cart);
                    
                    Order::store($cart, $patient);
                    
                }

            } else if ($cart->status_id == 3) {                 
                
                if (!Auth::user()->canApprovePayment($cart)) {
                    abort('500', 'Cannot Approve Payment');
                }
                
                CartStep::store($request->cart_id, $cart->status_id, 3, $request->remark, $discount_id);

                CartStep::nextStatus($cart->id);

            } elseif ($cart->status_id == 4) {
                    //Patient Registration

                    $patient = Patient::register($cart);
                    if ($cart->hasProductCategories([1])) {
                        $this->sendserviceCatalogue($cart); 
                    }


                    //Place order
                    Order::store($cart, $patient);
                    CartStep::store($request->cart_id, $cart->status_id, 3, $request->remark, $discount_id); 
            } 

            return ['message' => 'Cart Approved', 'status' => 'Success!'];
            
        } 
        //Request State = 2 ie. Cart Rejected
        CartStep::store($request->cart_id, $cart->status_id, 2, $request->remark);

        return ['message' => 'Cart Rejected', 'status' => 'Success!'];
    }

   public  function  sendserviceCatalogue($cart)
    {
        $products_id = [];
        $products = CartProduct::where('cart_id' , $cart->id)->get();
        foreach ($products as $product) {
            $products_id[] = $product->product_id;
        }
        
        
        if(in_array(73, $products_id)){
            $this->sendEmail($this->template_1,$cart->lead_id);
        } //without free herbs
        elseif(in_array(64, $products_id)){
            $this->sendEmail($this->template_1w,$cart->lead_id);
        }
        elseif(in_array(93, $products_id)){
            $this->sendEmail($this->template_2,$cart->lead_id);
        } //without free herbs
        elseif(in_array(92, $products_id)){
            $this->sendEmail($this->template_2w,$cart->lead_id);
        } //with free herbs   
        elseif(in_array(5, $products_id)){
            $this->sendEmail($this->template_3,$cart->lead_id);
        } //without free herbs
        elseif(in_array(52, $products_id)){
            $this->sendEmail($this->template_3w,$cart->lead_id);
        } //with free herbs
        elseif(in_array(6, $products_id)){
            $this->sendEmail($this->template_6,$cart->lead_id);
        } //without free herbs
        elseif(in_array(53, $products_id)){
            $this->sendEmail($this->template_6w,$cart->lead_id);
        } //with free herbs
        else if(in_array(7, $products_id)){
            $this->sendEmail($this->template_12,$cart->lead_id);
        } //without free herbs
        elseif(in_array(54, $products_id)){
            $this->sendEmail($this->template_12w,$cart->lead_id);
        } //with free herbs*/

    }
    public function  sendEmail($template_id , $user)
    {
        $template = EmailTemplate::find($template_id);
        $subject = $template->subject;
        $from =  $template->from;//'service@nutrihealthsystems.com';
        $email = new Email;
        $email->user_id = Auth::user()->id;
        $email->lead_id = $user;
        $email->template_id = $template_id;
        $body = $template->email;

        $lead = null;
        $lead = Lead::where('id' , $user)->first();

        if($lead->email) {
        $body = $template->email;
        Mail::send('templates.emails.empty', array('body' => $body), function($message) use ($subject, $from,$lead)
        {
        $message->to($lead->email,$lead->name)
        ->subject($subject)
        ->from($from, 'Nutri-Health Systems');
        });
        $email->email = $body;
        $email->save();
        }   

    }

     public function inventryDatabase($cart)
    {
        $cart = Cart::
                with('address' ,'lead' , 'products' , 'payments.method' , 'cre' , 'source')
                ->where('id' , $cart->id)
                ->first();


        

        $crename = $cart->cre->username;
        $source = $cart->source->source;
        $discount;
        $shippingAddress;
        $ModeOfPayment = " ";
        if($cart->address)
        {
            $shippingAddress = $cart->address->address .','.$cart->address->city . ',' . $cart->address->region->region_name . ',' . $cart->address->zip . ',' . $cart->address->country;
        }
        else
        {
             $shippingAddress = $cart->lead->address .','.$cart->lead->city . ',' . $cart->lead->zip . ',' . $cart->lead->state . ',' . $cart->lead->country;
        }

        foreach ($cart->payments as $payment) {
                
            $ModeOfPayment = $ModeOfPayment.'/'.$payment->method->name;
        }

       

         $users = DB::connection('sqlsrv')->table('tblCustomerDetail')->where('CustomerNo', $cart->lead->id)->first();

        if($users == null)
        {
            $users = DB::connection('sqlsrv')->table('tblCustomerDetail')->insert(
                            ['CustomerNo' => $cart->lead->id, 'CustomerName' => $cart->lead->name ,'PrintName' => ' ', 'ContectName' => $cart->lead->name ,'Address' => $cart->lead->address, 'City' => $cart->lead->city ,'Pincode' => $cart->lead->zip, 'MobileNo' => $cart->lead->mobile ,'Phone_No' =>  $cart->lead->phone, 'TIN' => ' ' ,'DateofBirth' => $cart->lead->dob, 'dateofAnniversary' => ' ' ,'TaxType' => ' ', 'TaxRate' => ' ' ,'Sex' => $cart->lead->gender, 'MaritalStatus' => '' ,'LedgerName' => $cart->lead->name.'-'. $cart->lead->id, 'Email' => $cart->lead->email ,'OPPts' => ' ', 'BillDis' => ' ' ,'Active' => ' ', 'StateCode' => $cart->lead->state ,'PAN' => '', 'CSTNo' => '1' ,'Fax' => '1', 'Country' => $cart->lead->country ,'State' => $cart->lead->state]);


            $users = DB::connection('sqlsrv')->table('tblLedgers')->insert(
                            ['LedgerName ' => $cart->lead->name.'-'. $cart->lead->id, 'Description' => ' ' ,'GroupName' => 'sundry debtors', 'Street' => $cart->lead->address ,'City' => $cart->lead->city, 'PhNo' =>$cart->lead->mobile ,'DROpening' => 0 , 'CROpening' => 0  ,'OpeningType' =>  'DR', 'AccountDate' => date('Y-m-d H:i:s') ,'Email' => $cart->lead->email, 'companylocation' => ' ' ,'InventoryFlag' => '1']);


                  
        }

        //$findcartid = DB::connection('sqlsrv')->table('tblSaleOrderMaster')->where('VoucherNo', $cart->id)->get();

        //if($findcartid != null){

            DB::connection('sqlsrv')->table('tblSaleOrderMaster')->where('VoucherNo', $cart->id)->delete();

       // }
        
            

        $users = DB::connection('sqlsrv')->table('tblSaleOrderMaster')->insert(
                    ['VoucherNo' => $cart->id, 'OrderDate' => $cart->created_at ,'BillNo' => $cart->currency_id , 'DeliveryDate' => ' ' ,'LrNo' => ' ', 'LrDate' => date('Y-m-d H:i:s') ,'CustomerNumber' => $cart->lead->id, 'CompanyLocation' => ' ' ,'BillDiscRt' =>  0 , 'BillDiscAmt' => 0 ,'GrossAmount' =>  $cart->amount, 'totalAmt' => $cart->amount ,'totalQty' => $cart->products->count() , 'totalDiscAmt' => 0 ,'Remarks' => ' ', 'UserName' => $cart->lead->name ,'RefNo' => '', 'TotalTaxAmt' => ' ' ,'TotalExcise' => ' ', 'NetAmount' =>  $cart->amount ,'Active' => ' ', 'AdvanceAmt' => ' ' , 'CrName' => $crename , 'CartPayment' => $cart->payment , 'Source' => $source , 'ShippingAddress' => $shippingAddress , 'PaymentMode' => $ModeOfPayment ]
            );

        

       
        
            DB::connection('sqlsrv')->table('tblSaleOrderDetail')->where('VoucherNo', $cart->id)->delete();
        
        

        foreach ($cart->products as $product) {
            
            if($product->pivot->discount == null)
            {
                $discount = 0;
            }
            else
            {
                $discount = $product->pivot->discount;
            }

            $product_id = $product->id;
            if(array_key_exists($product_id, $list))
            {
                 $product_id =  $list[$product_id];
            }
            else
            {
               $product_id = $product->id;
            }

            $users = DB::connection('sqlsrv')->table('tblSaleOrderDetail')->insert(
                        ['VoucherNo' => $cart->id , 'ItemCode' =>  $product_id ,'ItemName' => $product->name , 'ColorName' => ' ' ,'Size' => ' ', 'Quantity' => $product->pivot->quantity , 'Unit' => 'Pcs' , 'SaleRate' => $product->pivot->price ,'MRP' =>  $product->pivot->price , 'PurRate' => 0 ,'ItemDiscRate' => $discount , 'ItemDiscAmt' => ($product->pivot->price - $product->pivot->amount ) ,'TaxRate' => 0 , 'TaxAmt' => 0 ,'Amount' => $product->pivot->amount , 'Excise' => 0 ,'Barcode' => ' ', 'SerialNo' => ' ' ,'DisplayOrd' => ' ', 'TaxType' => 0,'OtherTaxRate' =>  0 , 'OtherTaxAmt' => 0 ]
                );

        }
        

      return true ; 
    }
}