<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class QuizReattempt extends Model {
    protected $table = 'quiz_reattempt';
    protected $fillable = ['user_id', 'quiz_id', 'questions', 'duration'];

  
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function quiz() {
        return $this->belongsTo(QuizSetting::class, 'quiz_id', 'id');
    }
    

}
