<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Source extends Model
{
    use SoftDeletes;
    
    protected $table = "m_lead_source";

    public function channel()
    {
    	return $this->belongsTo(Channel::class);
    }
}
