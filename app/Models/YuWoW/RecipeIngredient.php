<?php

namespace App\Models\YuWoW;

use Illuminate\Database\Eloquent\Model;

class RecipeIngredient extends Model
{
    protected $table = 'yuwow_alpha_1_0.recipe_ingredients';

    public function recipe()
    {
    	return $this->belongsTo(Recipe::class, 'recipe_code','recipe_code');
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'ingr_item_id', 'ID');
    }
}
