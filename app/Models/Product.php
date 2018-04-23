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
    protected static $wtLossProductIds = [55,57,58];
    //products.id => products.duration
    protected static $basePlans = array(
                    2 => 1,
                    3 => 7,
                    4 => 30,
                    5 => 90,
                    6 => 180,
                    7 => 365, 
                    93 => 60,
            );

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

    public static function getWtLossProductIds()
    {
        return self::$wtLossProductIds;
    }

    public static function getBasePlanByDuration($duration)
    {
        $key = array_search($duration, self::$basePlans);

        if($key) {
            return Product::find($key);
        } else {
            return false;
        }        
        
    }

    public static function getBasePlanIds()
    {
        return array_keys(self::$basePlans);
    }
}
