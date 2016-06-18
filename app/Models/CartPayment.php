<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException as Exception;
use Auth;
use Carbon;
use App\Models\CartStep;

class CartPayment extends Model
{
    protected $fillable = [
        'cart_id',
        'payment_method_id',
        'date',
        'amount',
        'delivery_time',
        'remark',
        'created_by'
    ];

    public function setDateAttribute($value)
    {
        $this->attributes['date']   = $value ? Carbon::parse($value) : null;
    }

    public function setDeliveryTimeAttribute($value)
    {
        $this->attributes['delivery_time']   = $value ? Carbon::parse($value)->format('H:i') : null;
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function store($request, $cartId)
    {
        try {
            if(CartPayment::isPartPayment($cartId)) {
                $status_id = 3;
                $state_id = 1;
                $cart = Cart::find($cartId);
                $cart->status_id = $status_id;
                $cart->state_id = $state_id;
                $cart->save();

                $step = CartStep::store($cartId, 1, 3);
                $step = CartStep::store($cartId, $status_id, $state_id);
            }


            $payment = new CartPayment;

            $payment->cart_id           = $cartId;
            $payment->amount            = $request->amount;
            $payment->payment_method_id = $request->payment_method;
            $payment->date              = $request->date? Carbon::parse($request->date) : Carbon::now();
            $payment->remark            = $request->remark;
            $payment->created_by        = Auth::id();
            $payment->save();

            Cart::updatePayment($cart);

            return true;
            
        } catch (Exception $e) {
            return false;
        }
            
    }

    public static function isPartPayment($id) 
    {
        $cart = Cart::find($id);

        if ($cart->payments->isEmpty() && $cart->status_id <> 4) {
            return false;
        }

        return true;
    }
}
