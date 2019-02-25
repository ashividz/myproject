<?php

namespace App\Models\VediqueDiet;

use Illuminate\Database\Eloquent\Model;
use DB;

class Recipe extends Model
{
    protected $connection = 'VediqueDiet';
    protected $table = 'Recipe';
    public $timestamps = false;
    
}
