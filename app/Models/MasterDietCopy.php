<?php

namespace App\Models;

use Carbon\Carbon;
use Storage;
use Mail;
use Auth;
use DB;

use App\Support\SMS;

use Illuminate\Database\Eloquent\Model;

class MasterDietCopy extends Model
{
	protected $table = 'master_diet_copy';
	protected $fillable = ['patient_id', 'day', 'prakriti', 'blood_group', 'rh_factor', 'date_assign', 'program_id', 'nutritionist','breakfast', 'mid_morning', 'lunch' , 'evening' , 'dinner' , 'isapproved' , 'created_at' , 'updated_at' , 'deleted_at' ];
	
}