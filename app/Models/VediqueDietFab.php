<?php

namespace App\Models;

use Carbon\Carbon;
use Storage;
use Mail;
use Auth;
use DB;

use App\Support\SMS;

use Illuminate\Database\Eloquent\Model;

class VediqueDietFab extends Model
{
	protected $connection = 'VediqueDiet';
	protected $table = 'user_fab';
	public $timestamps = false;
	protected $fillable = ['email','content'];
	
}