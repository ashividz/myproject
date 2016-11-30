<?php

namespace App\Models\YuWoW;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $table = 'yuwow_alpha_1_0.ingredients';

    public function recipes()
    {
        return $this->hasMany(RecipeIngredient::class, 'ingr_item_id','ID');
    }
}
