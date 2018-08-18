<?php

namespace App\Models;

use Carbon\Carbon;
use Storage;
use Mail;
use Auth;
use DB;

use App\Support\SMS;

use Illuminate\Database\Eloquent\Model;

class VediqueDietPaidDiet extends Model
{
	protected $connection = 'VediqueDiet';
	protected $table = 'Diets';
	public $timestamps = false;
	protected $fillable = ['email', 'date_assign', 'date', 'early_morning', 'breakfast', 'mid_morning', 'lunch', 'evening','dinner', 'post_dinner', 'herbs' ];
	
}