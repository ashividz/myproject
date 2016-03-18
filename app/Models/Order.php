<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon\Carbon;

use App\Models\ProductCategory;

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

    public static function store($cart)
    {
        $categories = ProductCategory::get();

        foreach ($categories as $category) {
            
            $duration = CartProduct::getDietDuration($cart->id, $category->id);

            if ($duration > 0) {
                $order = new Order;

                $order->patient_id              = $cart->lead->patient ? $cart->lead->patient->id : null;
                $order->cart_id                 = $cart->id;
                $order->product_category_id     = $category ->id;
                $order->duration                = $duration;
                $order->created_by              = 1;//Auth::id();
                $order->save();
            }
        }


        //Update old Fee tables for now
        if (isset($cart->lead->patient)) {
            OrderPatient::fee($cart->lead->patient, $cart);
        } 
    }
}