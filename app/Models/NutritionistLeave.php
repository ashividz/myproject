<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use Auth;

class NutritionistLeave extends Model
{
   
    protected $table = 'nutritionist_leave';

   public static function getNutritionistId()
   {
       return NutritionistLeave::where('created_at', '>=', date('Y-m-d').' 00:00:00')->get();
   }  
}
