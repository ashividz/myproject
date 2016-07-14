<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class QuizUser extends Model {
    protected $table = 'quiz_user';
    protected $fillable = ['user_id', 'quiz_id', 'q_group'];

  
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function quiz() {
        return $this->belongsTo(QuizSetting::class, 'quiz_id', 'id');
    }
    

}
