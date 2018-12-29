<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\ProductOffer;
use App\Models\CartProduct;

use DB;

class CartProduct extends Model
{
    protected $table ="cart_product";

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function offer()
    {
        return $this->belongsTo(Product::class, 'product_offer_parent_id');
    }

    public static function getOffer($cartProduct)
    {
        $offers = CartProduct::checkOffer($cartProduct); 

        if($offers)
        {
            foreach ($offers as $offer)
            {
                $op = new CartProduct;
                $op->cart_id = $cartProduct->cart_id;
                $op->product_id = $offer->product_offer_id;
                $op->product_offer_parent_id = $cartProduct->product_id;
                $op->product_offer_id = $offer->id;
                $op->quantity = $offer->product_offer_quantity;
                $op->price =  $offer->product_offer_price;
                $op->amount = $offer->product_offer_quantity * $offer->product_offer_price;
                $op->created_by = 1;
                $op->save();
            }
            
        }
    }

    public static function updateOffer($cartProduct)
    {
        /*$offer = ProductOffer::where('product_id', $cartProduct->product_id)
                     ->where('minimum_quantity', '<=', $cartProduct->prevQuantity)
                    //->where('start_date', '<=', date('Y-m-d'))
                    //->where('end_date', '<', date('Y-m-d'))
                    ->cartBy('product_offer_quantity', 'desc')
                    ->first();
                    //dd($offer);
        if ($offer) {
            $offer->offerProduct->delete();
        }*/
        //Delete all existing Offers
        CartProduct::deleteOffer($cartProduct);
        

        $newOffer = CartProduct::checkOffer($cartProduct);

        //dd($newOffer);

        if($newOffer) {
            $op = new CartProduct;
            $op->cart_id = $cartProduct->cart_id;
            $op->product_id = $newOffer->product_offer_id;
            $op->product_offer_id = $newOffer->id;
            $op->quantity = $newOffer->product_offer_quantity;
            $op->price = 0;
            $op->discount = 0;
            $op->amount = 0;
            $op->created_by = 1;
            $op->save();
        }
    }

    private static function checkOffer($cartProduct)
    {
        return ProductOffer::where('product_id', $cartProduct->product_id)
                    ->where('minimum_quantity', '<=', $cartProduct->quantity)
                    //->where('start_date', '<=', date('Y-m-d'))
                    //->where('end_date', '<', date('Y-m-d'))
                    ->orderBy('product_offer_quantity', 'desc')
                    ->get();
    }

    public static function deleteOffer($cartProduct)
    {
        CartProduct::where('cart_id', $cartProduct->cart_id)
                    ->where('product_offer_parent_id', $cartProduct->product_id)
                    ->delete();
    }

    public static function getDietDuration($cart) 
    {
        return DB::table('cart_product as op')
                    ->join('products as p', 'op.product_id', '=', 'p.id')
                    ->where('cart_id', $cart->id)
                    ->where('product_category_id', 1)
                    ->sum(DB::RAW('duration * quantity'));
                    //->first();
    }

    

    /*public static function hasProductCategory($cart, $product_category_id)
    {
        //$cart = Cart::with('products')->find($id);

        foreach ($cart->products as $product) {
            if ($product->product_category_id == $product_category_id) {
                return true;
            }
        }
        return false;
    }  */

    public static function checkProduct($cart)
    {
        //Don't use calculation for discounted product based on amount calculated using discount
        //$amount = ($cart->getDietPaidAmount() *100) / (100 - $cart->getDietDiscount());
        $basePlanIds = Product::getBasePlanIds();
        $amount = $cart->getDietPaidAmount();

        //echo "Non discounted Amount : ". $amount ."<p>";
        //return $amount;
        $product = Product::where('product_category_id', 1)
                    ->whereIn('id',$basePlanIds)
                    ->whereNull('extension');
                    
        if ($cart->currency_id == 2) {
            $product->where('international_price_usd', '<=' , $amount)
                ->orderBy('international_price_usd', 'desc');

        } elseif ($cart->lead->country == "IN") {
            $product->where('domestic_price_inr', '<=' , $amount)
                ->orderBy('domestic_price_inr', 'desc');

        } else {
            $product->where('international_price_inr', '<=' , $amount)
                ->orderBy('international_price_inr', 'desc');
        }                

        return $product->select('duration')->first();
    }

    public static function getPlanPriceByDuration($cart, $duration)
    {
        $product = Product::getBasePlanByDuration($duration);

        if ( !$product ) {
            return false;
        }

        if ($cart->currency_id == 2) {
            return $product->international_price_usd;
        } elseif ($cart->lead->country == "IN") {
            return $product->domestic_price_inr;
        } else {
            return $product->international_price_inr;
        }
    }
}
