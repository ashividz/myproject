<?php

namespace App\Models\YuWoW;

use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;

class SentRecipe extends Model
{
    //protected $table = 'yuwow_alpha_1_0.sent_recipies';

    public function patient()
    {
    	return $this->belongsTo(Patient::class, 'id','patient_id');
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class, 'recipe_code','recipe_code');
    }

    public function ingredients()
    {
        return $this->hasMany(SentIngredient::class, 'recipe_code','recipe_code');
    }


}
