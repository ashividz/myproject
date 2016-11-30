<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class Pipeline extends Model {
    
    protected $fillable = [
        'lead_id',
        'date',
        'currency_id',
        'price',
        'discount',
        'amount',
        'remark',
        'created_by'
    ];

    public function setCreatedByAttribute()
    {
        $this->attributes['created_by'] = Auth::id();
    }

    public function setDateAttribute($date)
    {
        $this->attributes['date'] = \Carbon::parse($date)->format('Y-m-d');
    }

    public function getDateAttribute($date)
    {
        return \Carbon::parse($date)->format('jS M, Y');
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function state()
    {
        return $this->belongsTo(CartState::class, 'state_id');
    }

}