<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use Auth;

class CartStep extends Model
{
    //protected $table = 'order_status';

    /*public function steps()
    {
        $this->hasMany(States::class);
    }*/

    public function status()
    {
        return $this->belongsTo(CartStatus::class, 'status_id');
    }

    public function state()
    {
        return $this->belongsTo(CartState::class, 'state_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public static function getCartStepByStatus($id, $status, $updated_at = null)
    {
        $query =  CartStep::where('cart_id', $id)
                        ->where('status_id', $status)
                        ->with('state')
                        ->with(['user' => function($q) {
                            $q->withTrashed();
                        }]);
        if ($updated_at) {
            //dd($updated_at);
            $query->where('updated_at', '>=', $updated_at);
        }

        return  $query->orderBy('id', 'desc')
                        ->limit(1)
                        ->first();
    }

    public static function store($cart_id, $status_id, $state_id, $remark = null, $discount_id = null)
    {
        $step = new CartStep;

        $step->cart_id          = $cart_id;
        $step->status_id        = $status_id;
        $step->state_id         = $state_id;
        $step->discount_id      = $discount_id;
        $step->remark           = $remark;
        $step->created_by       = Auth::id();
        $step->save();

        Cart::updateStatus($cart_id, $status_id); 
        Cart::updateState($cart_id, $state_id); 
    }

    public static function nextStatus($id)
    {
        $cart = Cart::find($id);

        if ($cart) {
            $step = new CartStep;

            $step->cart_id         = $cart->id;
            $step->status_id        = $cart->status_id + 1;
            $step->state_id         = 1;
            $step->created_by       = Auth::id();
            $step->save();

            Cart::updateStatus($cart->id, $cart->status_id + 1); 
            Cart::updateState($cart->id, 1); 
        }        
    }

    public static function startState($id, $remark = null)
    {
        $cart = Cart::find($id);

        if ($cart) {
            $step = new CartStep;

            $step->cart_id         = $cart->id;
            $step->status_id        = $cart->status_id;
            $step->state_id         = 1;
            $step->remark           = $remark;
            $step->created_by       = Auth::id();
            $step->save();

            Cart::updateStatus($cart->id, $cart->status_id); 
            Cart::updateState($cart->id, 1); 
        }        
    }
        
}