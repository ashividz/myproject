<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VediqueDietRecipe extends Model
{
    protected $fillable = [
        'name',
        'cooking_time',
        'serving',
        'calories',
        'steps',
        'tips',
        'tag',
        'image',
        'ingredients',
        'prakriti'
    ];
    public $timestamps = false;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $table = 'vediqueDietRecipe';
}
