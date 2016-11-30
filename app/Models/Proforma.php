<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proforma extends Model
{
    protected $fillable = [
        'cart_id',
        'status_id',
        'created_by'
    ];
}
