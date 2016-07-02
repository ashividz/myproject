<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class QuizSetting extends Model {
    protected $table = 'quiz_setting';
	protected $fillable = ['question_group', 'start_time', 'end_time', 'quiz_duration', 'created_at', 'active'];

    public function questions() {
        return $this->hasMany(QuizQuestion::class, 'id', 'quiz_id');
    }
    
}
