<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon\Carbon;

use App\Models\ProductCategory;
use App\Models\Fee;

class Order extends Model
{
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function store($cart, $patient = null)
    {
        $categories = ProductCategory::get();

        foreach ($categories as $category) {
            
            $duration = CartProduct::getDietDuration($cart->id, $category->id);

            if ($duration > 0) {
                $order = new Order;

                $order->patient_id              = $patient->id;
                $order->cart_id                 = $cart->id;
                $order->product_category_id     = $category ->id;
                $order->duration                = $duration;
                $order->created_by              = Auth::id();
                $order->save();
            }
        }


        //Update old Fee tables for now
        if ($patient) {
            foreach ($cart->payments as $payment) {
                Fee::store($patient, $payment);
            }    
        } 
    }
}