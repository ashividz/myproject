<?php

namespace App\Models;

use Carbon\Carbon;
use Storage;
use Mail;
use Auth;
use DB;

use App\Support\SMS;

use Illuminate\Database\Eloquent\Model;

class VediqueDietPaidRecommendation extends Model
{
	protected $connection = 'VediqueDiet';
	protected $table = 'paid_user_recommendation';
	public $timestamps = false;
	protected $fillable = ['email','program_id'];
	
}