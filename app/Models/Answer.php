<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class Answer extends Model {
protected $table = 'quiz_answers';
	protected $fillable = [
        'description',
        'is_correct'
    ];

}