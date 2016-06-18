<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon\Carbon;

use App\Models\ProductCategory;
use App\Models\Fee;

class Order extends Model
{
    protected $fillable = [
        'cart_id'
    ];

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
        /*if (!$cart->orders->isEmpty()) {
            return $cart;
        }*/

        //$duration = CartProduct::getDietDuration($cart, 1);
        $cart = Cart::setDietDuration($cart);

        if ($cart->duration > 0) {
            $order = Order::firstOrNew(['cart_id' => $cart->id]);

            $order->patient_id              = $order->patient_id ? : $patient->id;
            $order->cart_id                 = $order->cart_id ? : $cart->id;
            $order->product_category_id     = $order->product_category_id ? : 1;
            $order->duration                = $cart->duration;
            $order->created_by              = Auth::id();
            $order->save();
        }
        /*$categories = ProductCategory::get();

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
        }*/


        //Update old Fee tables for now
        if ($patient) {
            Fee::store($cart, $patient);
            /*foreach ($cart->payments as $payment) {
                Fee::store($payment);
            } */   
        } 
    }
}