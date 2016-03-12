<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{   
    public function category()
    {
        return $this->belongsTo(ProductCategory::class ,'product_category_id');
    }

    public function unit()
    {
        return $this->belongsTo(ProductUnit::class);
    }

    public function offers()
    {
        return $this->hasMany(ProductOffer::class);
    }
}
