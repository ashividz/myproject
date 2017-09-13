<?php

namespace App\Models;

use Carbon\Carbon;
use Storage;
use Mail;
use Auth;
use DB;

use App\Support\SMS;

use Illuminate\Database\Eloquent\Model;

class Master_Diet extends Model
{
	protected $table = 'master_diet';
	protected $fillable = ['Breakfast','MidMorning','Lunch','Evening','Dinner','Condition_ID','Program_ID','updated_at','created_id','Day_Count'];
	
}