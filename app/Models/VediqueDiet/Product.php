<?php

namespace App\Models\VediqueDiet;

use Illuminate\Database\Eloquent\Model;
use DB;

class Product extends Model
{
    protected $connection = 'VediqueDiet';
    protected $table = 'Products';
    public $timestamps = false;
    
}
