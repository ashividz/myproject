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

    public function proforma()
    {
        return $this->hasOne(Proforma::class, 'cart_id');
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
    
    public function status()
    {
        return $this->belongsTo(CartStatus::class);
    }

    public function state()
    {
        return $this->belongsTo(CartState::class, 'state_id');
    }

    public function approvers()
    {
        return $this->hasMany(CartApprover::class, 'status_id', 'status_id');
    }
    

    public function statuses()
    {
        return $this->hasMany(CartStep::class);
    }
    
    public function step()
    {
        return $this->hasOne(CartStep::class)->latest();
    }

    public function steps()
    {
        return $this->hasMany(CartStep::class)->orderBy('id', 'desc');
    }

    public function payments()
    {
        return $this->hasMany(CartPayment::class)->orderBy('id', 'desc');
    }

    public function shippings()
    {
        return $this->hasMany(Shipping::class, 'cart_id', 'id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function comments()
    {
        return $this->hasMany(CartComment::class, 'cart_id')
                    ->orderBy('id', 'desc');
    }

    public function fee()
    {
        return $this->hasOne(Fee::class, 'cart_id');
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
}
