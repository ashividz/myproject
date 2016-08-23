<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BenefitCart extends Model
{
    protected $fillable = ['benefit_lead_id', 'reference_id'];
   
   

     public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function benefit()
    {
        return $this->hasOne(BenefitLead::class, 'benefit_lead_id', 'id');
    }
  
}
