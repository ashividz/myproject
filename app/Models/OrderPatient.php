<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class OrderPatient extends Model
{
    protected $table = 'order_patient';

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public static function store($patient, $order)
    {
        $duration = CartProduct::getDietDuration($order->id);

        if ($duration) {
            $po = new OrderPatient;

            $po->patient_id = $patient->id;
            $po->order_id = $order->id;
            $po->duration = $duration;
            $po->created_by = 1;//Auth::id();
            $po->save();

            //Update old Fee tables for now
            OrderPatient::fee($patient, $order);
        }

        
    }

    public static function fee($patient, $cart)
    {
        foreach ($cart->payments as $payment) {
            Fee::store($patient, $payment);
        }        
    }
}