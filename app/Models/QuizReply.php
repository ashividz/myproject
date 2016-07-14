<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class QuizReply extends Model {
protected $table = 'quiz_replies';
    protected $fillable = ['quiz_question_id', 'user_answer', 'is_correct', 'duration'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function question()
    {
        return $this->belongsTo(QuizQuestion::class,'quiz_question_id','id');
    }
    public function answer()
    {
        return $this->belongsTo(QuizAnswer::class,'user_answer','id');
    }
    
}
