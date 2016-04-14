<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class Reply extends Model {
protected $table = 'quiz_replies';
	protected $fillable = ['quiz_question_id', 'is_correct', 'duration'];

}
