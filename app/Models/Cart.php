<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class Cart extends Model
{
    protected $fillable = [
        'lead_id',
        'status_id',
        'state_id',
        'currency_id',
        'amount',
        'payment',
        'balance',
        'coupon',
        'start_date',
        'end_date',
        'source_id',
        'cre_id',
        'created_by',
        'shipping_address_id'
    ];

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

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(LeadAddress::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function updateAmount()
    {
        $this->amount = $this->products()->sum('amount');
        $this->payment = $this->payments()->sum('amount');
        $this->balance = $this->amount - $this->payment;
        $this->save();

        return $this;
    }

    /*public function updatePayment()
    {       

        //$payment = CartPayment::where('cart_id', $id)->sum('amount');
        
        $this->payment = $this->payments()->sum('amount');
        //$cart->save();

        $this->balance = $this->amount - $this->payment;
        $this->save();
        //dd($cart);
        return $this; 
    }*/

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

        /** Cart Notification **/
        if ($state == 2) {
            Notification::store(5, $id, $cart->cre_id);
        } elseif ($state == 3 && $cart->status_id == 2) {
            Notification::store(2, $id, $cart->cre_id);
        } elseif ($state == 3 && $cart->status_id == 3) {
            Notification::store(3, $id, $cart->cre_id);
        } elseif ($state == 3 && $cart->status_id == 4) {
            Notification::store(4, $id, $cart->cre_id);
        }
    }

    public function hasProductCategories($id)
    {
        return $this->products()->whereIn('product_category_id', $id)->first();
        //dd($this->whereHas('products', function($q) use ($id){
           // $q->where('product_category_id', $id);
        //})->get());//->where('product_category_id', 2));
                    //->first();
        /*foreach ($cart->products as $product) {
            if ($product->product_category_id == $product_category_id) {
                return true;
            }
        }*/
        /*if ($products) {
            return 
        }
        return false;*/
    }

    /*public static function isIncompleteCart($lead)
    {
        return $lead->carts()->where('status_id', '<=', 4)
                        ->where('state_id', '<>', 3)
                        ->first();
    }*/

    public static function setDietDuration($cart)
    {
        $amount = $cart->getDietAmount();
        $paid = $cart->getDietPaidAmount();

        $duration = CartProduct::getDietDuration($cart);

        //echo "Diet Amount : ".$amount."<p>Paid : ".$paid."<p>";

        if ($paid == $amount) {

            $cart->duration = $duration; 

        } elseif ($paid > $amount) {

            $product = CartProduct::checkProduct($cart);

            if ($product) {
                   $cart->duration = $product->duration;

            } else {
                $cart->duration = $duration > 60 ? $duration : 60;

            }   
            
        } elseif ($paid >= 4000 && $paid < 8000) {

            $cart->duration = 30;

        } elseif ($paid >= 8000 && $paid < 11000) { 
            $cart->duration = 60;

        } else {
            $product = CartProduct::checkProduct($cart);
            $cart->duration = $product->duration;
        }

        return $cart;
    }

    public function getDietAmount()
    {
        return $this->products()
            ->whereIn('product_category_id', [1])
            ->sum('amount');
    }

    public function getDietPaidAmount()
    {
        $diet_amount = $this->products()
            ->whereIn('product_category_id', [1])
            ->sum('amount');

        $other_amount = $this->products()
            ->whereIn('product_category_id', [2,3,4])
            ->sum('amount');

        $payment = $this->payments()
            ->sum('amount');

        return $payment - $other_amount;
    }

    public function getDietDiscount()
    {
        return $this->products()
            ->whereIn('product_category_id', [1])
            ->max('discount');
    }

    public function discountSteps() //CartApproval
    {
        if ($this->status_id <> 2 || $this->products->isEmpty()) {
            return false;
        }

        $maxDiscount = !$this->products->isEmpty() ? max(array_pluck($this->products, 'pivot.discount')) : 0;

        if ($maxDiscount == 0) {
            return false;
        }

        $step = $this->steps()->whereNotNull('discount_id')->orderBy('created_at', 'desc')->first();

        $step4 = $this->steps()->where('status_id', 4)->first();
        //echo $step4->created_at;
        //dd($step4->created_at.$step->created_at);

        $discount_id = $step ? $step->discount_id + 1 : 1; //dd($discount_id);
        //dd($step4);
        if( $discount_id <> 1 && $step4 && $step4->created_at >= $step->created_at) { 
        //Part Payment or Extension
            $discount_id = 1;
        }        
        //dd($discount_id);
        $discount = Discount::where('value', '<=', $maxDiscount + 5)
                                ->where('id', $discount_id)->first(); 
        //dd($discount);

        if ($discount) {
            return $discount;
        }

        return false;
    }
}
