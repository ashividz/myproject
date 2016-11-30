<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Fee;

use DB;
use Auth;

class WebOrderMeta extends Model
{   
    protected $connection = 'mysql3';
    protected $table = "newwebwp.wp_postmeta";

    public function order()
    {
        return $this->belongsTo(WebOrder::class, 'ID', 'post_id');
    }

}
