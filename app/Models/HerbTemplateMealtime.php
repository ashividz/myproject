<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HerbTemplateMealtime extends Model
{
    public $timestamps = false;

    public function template()
    {
    	return $this->belongsTo(HerbTemplate::class);
    }

    public function mealtime()
    {
    	return $this->belongsTo(Mealtime::class);
    }

    public static function saveMealtime($herb_template_id, $mealtime_id)
    {
        $mealtime = new HerbTemplateMealtime;
        
        $mealtime->herb_template_id = $herb_template_id;
        $mealtime->mealtime_id = $mealtime_id;
        $mealtime->save();
    }
    
}