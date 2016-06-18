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

    public static function getOffer($CartProduct)
    {
        $offer = CartProduct::checkOffer($CartProduct); 

        if($offer) {
            $op = new CartProduct;
            $op->cart_id = $CartProduct->cart_id;
            $op->product_id = $offer->product_offer_id;
            $op->product_offer_parent_id = $CartProduct->product_id;
            $op->product_offer_id = $offer->id;
            $op->quantity = $offer->product_offer_quantity;
            $op->price = 0;
            $op->discount = 0;
            $op->amount = 0;
            $op->created_by = 1;
            $op->save();
        }
    }

    public static function updateOffer($CartProduct)
    {
        /*$offer = ProductOffer::where('product_id', $CartProduct->product_id)
                     ->where('minimum_quantity', '<=', $CartProduct->prevQuantity)
                    //->where('start_date', '<=', date('Y-m-d'))
                    //->where('end_date', '<', date('Y-m-d'))
                    ->cartBy('product_offer_quantity', 'desc')
                    ->first();
                    //dd($offer);
        if ($offer) {
            $offer->offerProduct->delete();
        }*/
        //Delete all existing Offers
        CartProduct::deleteOffer($CartProduct);
        

        $newOffer = CartProduct::checkOffer($CartProduct);

        //dd($newOffer);

        if($newOffer) {
            $op = new CartProduct;
            $op->cart_id = $CartProduct->cart_id;
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

    private static function checkOffer($CartProduct)
    {
        return ProductOffer::where('product_id', $CartProduct->product_id)
                    ->where('minimum_quantity', '<=', $CartProduct->quantity)
                    //->where('start_date', '<=', date('Y-m-d'))
                    //->where('end_date', '<', date('Y-m-d'))
                    ->orderBy('product_offer_quantity', 'desc')
                    ->first();
    }

    public static function deleteOffer($CartProduct)
    {
        CartProduct::where('cart_id', $CartProduct->cart_id)
                    ->where('product_offer_parent_id', $CartProduct->product_id)
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
        $amount = ($cart->getDietPaidAmount() *100) / (100 - $cart->getDietDiscount());
        //echo "Non discounted Amount : ". $amount ."<p>";
        //return $amount;
        $product = Product::where('product_category_id', 1)                            
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

        return $product
            ->select('duration')
            ->first();
    }
}
