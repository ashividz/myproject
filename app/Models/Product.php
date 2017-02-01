<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{   
    use SoftDeletes;

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

    protected static $herbIds = [8,9,10,11,12,13,14];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class ,'product_category_id');
    }

    public function offers()
    {
        return $this->hasMany(ProductOffer::class);
    }

    public static function getHerbIds()
    {
        return self::$herbIds;
    }
}
