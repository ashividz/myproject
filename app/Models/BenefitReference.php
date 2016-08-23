<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BenefitReference extends Model
{
    protected $fillable = ['benefit_lead_id', 'reference_id'];
   
   

     public function reference()
    {
        return $this->hasOne(Lead::class, 'id', 'reference_id');
    }

    public function benefit()
    {
        return $this->hasOne(BenefitLead::class, 'benefit_lead_id', 'id');
    }

    public function bcart()
    {
        return $this->hasOne(BenefitCart::class, 'benefit_lead_id', 'benefit_lead_id');
    }
  
}
