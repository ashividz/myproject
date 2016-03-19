<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOffer extends Model
{
    protected $fillable = [
        'product_id',
        'minimum_quantity',
        'product_offer_id',
        'product_offer_quantity',
        'start_date',
        'end_date',
        'created_by'
    ];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_offer_id');
    }

    public function offerProduct()
    {
        return $this->belongsTo(OrderProduct::class, 'id', 'product_offer_id');
    }
}
