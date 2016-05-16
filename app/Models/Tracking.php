<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon;
use Auth;

class Tracking extends Model
{
    protected $fillable  = [
        'id',
        'cart_id',
        'created_by'
    ];

    protected $hidden = array(
        'invoice',
        //'created_at',
        //'updated_at'
    );

    /** Status Detail **/
    public function setStatusDetailAttribute($value)
    {
        $this->attributes['status_detail'] = json_encode($value);
    }

    public function getStatusDetailAttribute($value)
    {
        return json_decode($value);
    }

    /** Service **/
    public function setServiceAttribute($value)
    {
        $this->attributes['service'] = json_encode($value);
    }

    public function getServiceAttribute($value)
    {
        return json_decode($value);
    }

    /** Package Weight **/
    public function setPackageWeightAttribute($value)
    {
        $this->attributes['package_weight'] = json_encode($value);
    }

    public function getPackageWeightAttribute($value)
    {
        return json_decode($value);
    }

    /** Shipment Weight **/
    public function setShipmentWeightAttribute($value)
    {
        $this->attributes['shipment_weight'] = json_encode($value);
    }

    public function getShipmentWeightAttribute($value)
    {
        return json_decode($value);
    }

    /** Special Handlings **/
    public function setSpecialHandlingsAttribute($value)
    {
        $this->attributes['special_handlings'] = json_encode($value);
    }

    public function getSpecialHandlingsAttribute($value)
    {
        return json_decode($value);
    }

    /** Shipper Address **/
    public function setShipperAddressAttribute($value)
    {
        $this->attributes['shipper_address'] = json_encode($value);
    }

    public function getShipperAddressAttribute($value)
    {
        return json_decode($value);
    }

    /** Destination Address **/
    public function setDestinationAddressAttribute($value)
    {
        $this->attributes['shipper_address'] = json_encode($value);
    }

    public function getDestinationAddressAttribute($value)
    {
        return json_decode($value);
    }

    /** Actual Delivery Address **/
    public function setActualDeliveryAddressAttribute($value)
    {
        $this->attributes['actual_delivery_address'] = json_encode($value);
    }

    public function getActualDeliveryAddressAttribute($value)
    {
        return json_decode($value);
    }

    /** Events **/
    public function setEventsAttribute($value)
    {
        $this->attributes['events'] = json_encode($value);
    }

    public function getEventsAttribute($value)
    {
        return json_decode($value);
    }

    /** Other Identifiers**/
    public function setOtherIdentifiersAttribute($value)
    {
        $this->attributes['other_identifiers'] = json_encode($value);
    }

    public function getOtherIdentifiersAttribute($value)
    {
        return json_decode($value);
    }

    /** Created By**/
    public function setCreatedByAttribute($value)
    {
        $this->attributes['created_by'] = Auth::id();
    }

    /**Created At**/
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('j M h:i A');
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function returned()
    {
        return $this->hasOne(Tracking::class, 'parent_id', 'id');
    }
}
