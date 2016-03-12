<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\CreateCartRequest;

use App\Models\Lead;
use App\Models\Cart;
use App\Models\CartStep;
use App\Models\LeadProgram;
use App\Models\OrderProduct;
use App\Models\WorkflowStatus;
use App\Models\Currency;
use App\Models\ProductCategory;
use Auth;
use Redirect;
use DB;

class CartController extends Controller
{
    public function index($id)
    {
        $lead = Lead::find($id);

        $statuses = WorkflowStatus::get();

        if (!$lead) {
            return "Lead Not found";
        }

        $currencies = Currency::get();

        $categories = ProductCategory::get();

        $data = array(
            'menu'          => 'lead',
            'section'       => 'partials.cart',
            'lead'          =>  $lead,
            'statuses'      =>  $statuses,
            'currencies'    =>  $currencies,
            'categories'    =>  $categories
        );

        return view('home')->with($data);
    }

    public function create($id)
    {
    	$lead = Lead::find($id);

        $currencies = Currency::get();

        $categories = ProductCategory::get();

    	if (!$lead) {
    		return "Lead Not found";
    	}

    	$data = array(
    		'lead' 		     =>	$lead,
            'currencies'     => $currencies,
            'categories'     => $categories
    	);

    	return view('cart.modal.create')->with($data);
    }

    public function store(CreateCartRequest $request)
    {
        //dd($request->programs);
        $lead = Lead::find($request->id);

        if ($lead) {

            return DB::transaction(function() use ($request, $lead) {

                $cart = new Cart;
                $cart->lead_id = $request->id;
                $cart->cre_id = $lead->cres->first()->cre;//$request->cre;
                $cart->source_id = $lead->source_id;//$request->source;
                $cart->currency_id = $request->currency;
                $cart->created_by = 1;//Auth::id();

                $cart->save();

                //Save the Programs
                //OrderProgram::store($cart->id, $request->programs);

                //Update the Order Amount
                //Cart::updateAmount($cart->id);

                //Create new OrderStep
                CartStep::store($cart->id, 1, 1);

                $data = array(
                    'message' => 'Cart Created', 
                    'status' => 'success'
                );

                return Redirect::to('/cart/'.$cart->id)->with($data);

            });

        } else {

            $data = array(
                'message' => 'Lead not found', 
                'status' => 'error'
            );


            return Redirect::to('/lead/'.$lead->id.'/order')->with($data);
        }           
            
    }

    public function process($id)
    {
        //Update OrderStep
        CartStep::store($id, 1, 3);

        //Create next OrderStep
        CartStep::nextStatus($id);

        $data = array(
            'message' => 'Order Processed', 
            'status' => 'success'
        );    

        return redirect('/cart/view/'.$id)->with($data);
    }

    public function show($id)
    {
        //Update the Order Amount
        Cart::updateAmount($id);
        
        $cart = Cart::with('currency', 'products.category','status', 'state', 'steps')
            ->find($id); 

        $statuses = WorkflowStatus::get();

        $data = array(
            'cart'     =>  $cart, 
            'statuses'  =>  $statuses
        );

        return view('cart.index')->with($data);
    }
    
}
