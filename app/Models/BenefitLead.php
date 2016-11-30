<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BenefitLead extends Model
{
    protected $fillable = ['lead_id', 'benefit_plan_id'];
   
   
     public function benefit()
    {
        return $this->hasOne(Benefit::class, 'benefit_id', 'id');
    }
    
     public function lead()
    {
        return $this->hasOne(Lead::class, 'lead_id', 'id');
    }

    public function plan()
    {
        return $this->hasOne(BenefitPlan::class, 'benefit_plan_id', 'id');
    }

      public function references()
    {
        return $this->hasMany(BenefitReference::class, 'id', 'benefit_lead_id');
    }

     public function cart()
    {
        return $this->hasOne(BenefitCart::class, 'id', 'benefit_lead_id');
    }

}
