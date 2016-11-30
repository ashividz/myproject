<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use DB;
use Auth;

class WebOrder extends Model
{
   protected $connection = 'mysql3';
   protected $table = "newwebwp.wp_posts";

   public function orderMetas()
    {
        return $this->hasMany(WebOrderMeta::class, 'post_id', 'ID');
    }


}