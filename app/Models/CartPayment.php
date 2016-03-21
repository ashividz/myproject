<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException as Exception;
use Auth;
use Carbon;

class CartPayment extends Model
{
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
            $payment = new CartPayment;

            $payment->cart_id           = $cartId;
            $payment->amount            = $request->amount;
            $payment->payment_method_id = $request->payment_method;
            $payment->date              = $request->date? Carbon::parse($request->date) : Carbon::now();
            $payment->remark            = $request->remark;
            $payment->created_by        = Auth::id();
            $payment->save();

            Cart::updatePayment($id);

            return true;
            
        } catch (Exception $e) {
            return false;
        }
            
    }
}
