<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon;
use Auth;

class Shipping extends Model
{
    protected $fillable = [
        'cart_id',
        'carrier_id',
        'tracking_id',
        'created_by'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }

    public function invoices()
    {
        return $this->hasMany(CartInvoice::class, 'cart_id', 'cart_id');
    }

    public static function updateStatus($id, $status, $estimated_delivery_timestamp, $actual_delivery_timestamp)
    {
        $shipping = Shipping::find($id);
        $shipping->status = $status;
        $shipping->estimated_delivery_timestamp = $estimated_delivery_timestamp;
        $shipping->actual_delivery_timestamp = $actual_delivery_timestamp;
        $shipping->save();
    }
}
