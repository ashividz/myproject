<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{   
    protected $fillable = [
            'product_category_id', 
            'name', 
            'description', 
            'duration', 
            'domestic_price_inr', 
            'international_price_inr', 
            'international_price_usd', 
            'offer'
        ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class ,'product_category_id');
    }

    public function offers()
    {
        return $this->hasMany(ProductOffer::class);
    }

    public function cart()
    {
        return $this->belongsToMany(Cart::class);
    }
}
