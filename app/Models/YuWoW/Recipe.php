<?php

namespace App\Models\YuWoW;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
   /*
  CREATE TABLE IF NOT EXISTS `sent_recipies` (
   `id` int(11) NOT NULL AUTO_INCREMENT,   
   `patient_id` int(11) NOT NULL,   
   `recipe_code` varchar(8) NULL,   
   `recipe_name` varchar(128) NULL,   
   `recipe_servings`  tinyint(4) NULL,  
   `recipe_calorie` mediumint(9) NULL,   
   `recipe_preparation_duration` varchar(8) NOT NULL,   
   `recipe_notes` varchar(1024) NULL,   
   `recipe_remarks` varchar(512) NULL,         
   `recipe_img_url` varchar(128) NULL,   
   `created_by` int(10) unsigned NOT NULL,   
   `created_at` datetime NOT NULL,   
   `updated_at` datetime NOT NULL,   
   PRIMARY KEY (`id`),   
   KEY `patient_id` (`patient_id`),   
   KEY `created_at` (`created_at`),   
   KEY `recipe_code` (`recipe_code`) 
   ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS `sent_ingredients` (  
`id` int(11) NOT NULL AUTO_INCREMENT,   
`patient_id` int(11) NOT NULL,   
`recipe_code` varchar(8) NULL,  
`ingredient_name` varchar(128) NULL,   
`quantity` varchar(8) NULL,   
`unit` varchar(12) NULL,   
`created_by` int(10) unsigned NOT NULL,  
`created_at` datetime NOT NULL,   
`updated_at` datetime NOT NULL,   
PRIMARY KEY (`id`)   
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


    */
    protected $table = 'yuwow_alpha_1_0.recipe';

    public function ingredients()
    {
    	return $this->hasMany(RecipeIngredient::class, 'recipe_code','recipe_code');
    }
}
