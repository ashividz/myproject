<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    
    protected $fillable = ['name']; 

    public function masterdiet()
    {
        return $this->hasMany( Master_Diet::class,'Program_ID');
        
    }
}
