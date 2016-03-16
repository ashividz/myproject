<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOffer extends Model
{
    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_offer_id');
    }

    public function offerProduct()
    {
        return $this->belongsTo(OrderProduct::class, 'id', 'product_offer_id');
    }
}
