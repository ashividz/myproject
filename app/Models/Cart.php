<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class Cart extends Model
{
    public function getDates()
    {
        return array('created_at', 'updated_at', 'deleted_at', 'date');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }
    
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    public function cre()
    {
        return $this->belongsTo(User::class, 'cre_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('id', 'product_offer_parent_id','product_offer_id', 'quantity', 'price', 'amount', 'discount', 'coupon', 'created_by')
            ->withTimestamps();
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
    
    public function state()
    {
        return $this->belongsTo(CartState::class, 'state_id');
    }    

    public function status()
    {
        return $this->belongsTo(CartStatus::class, 'status_id');
    }
    
    public function steps()
    {
        return $this->hasMany(CartStep::class)->orderBy('id', 'desc');
    }

    public function payments()
    {
        return $this->hasMany(CartPayment::class);
    }

    public static function updateAmount($id)
    {
        $cart = Cart::find($id);
        
        if(!$cart) {
            return "Cart not found";
        }

        $cart->amount = CartProduct::where('cart_id', $id)->sum('amount');
        $cart->save();
    }

    public static function updatePayment($id)
    {
        $cart = Cart::find($id);

        $cart->payment = CartPayment::where('cart_id', $id)->sum('amount');
        $cart->save();
    }

    public static function updateStatus($id, $status)
    {
        $cart = Cart::find($id);
        $cart->status_id = $status;
        $cart->save();
    }

    public static function updateState($id, $state)
    {
        $cart = Cart::find($id);
        $cart->state_id = $state;
        $cart->save();
    }

    public static function getOffer($id)
    {
        $cart = Cart::find($id);

        foreach ($cart->products as $product) {

            $offers = CartProduct::checkOffer($product); 

            foreach ($offers as $offer) {
                $op = new CartProduct;
                $op->cart_id = $id;
                $op->product_id = $offer->product_offer_id;
                $op->product_offer_parent_id = $product->id;
                $op->product_offer_id = $offer->id;
                $op->quantity = $offer->product_offer_quantity;
                $op->price = 0;
                $op->discount = 0;
                $op->amount = 0;
                $op->created_by = Auth::id();
                $op->save();
            }
        }
    }
}
