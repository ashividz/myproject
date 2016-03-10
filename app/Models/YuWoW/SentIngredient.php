<?php

namespace App\Models\YuWoW;

use Illuminate\Database\Eloquent\Model;

class SentIngredient extends Model
{
    //protected $table = 'yuwow_alpha_1_0.sent_ingredients';

    public function recipe()
    {
        return $this->belongsTo(SentRecipe::class, 'recipe_code','recipe_code');
    }
}
