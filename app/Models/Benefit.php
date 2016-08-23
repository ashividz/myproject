<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
{
    protected $fillable = ['description', 'product_id'];
   
   

     public function plan()
    {
        return $this->hasOne(Plan::class, 'id', 'benefit_id');
    }

  
}
