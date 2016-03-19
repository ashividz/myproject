<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException as Exception;
use Auth;

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

    public static function store($request, $id)
    {
        try {
            $payment = new CartPayment;

            $payment->cart_id          = $id;
            $payment->amount            = $request->amount;
            $payment->payment_method_id   = $request->payment_method;
            $payment->date              = $request->date? date('Y-m-d', strtotime($request->date)) : date('Y-m-d');
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
