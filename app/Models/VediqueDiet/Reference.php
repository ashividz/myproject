<?php

namespace App\Models\VediqueDiet;

use Illuminate\Database\Eloquent\Model;
use DB;

class Reference extends Model
{
    protected $connection = 'VediqueDiet';
    protected $table = 'user_reference';
    public $timestamps = false;

    
}
