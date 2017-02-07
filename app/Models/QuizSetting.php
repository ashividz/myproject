<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class QuizSetting extends Model {
    protected $table = 'quiz_setting';
	protected $fillable = ['question_group', 'start_time', 'end_time', 'quiz_duration', 'created_at', 'active'];

    public function questions() {
        return $this->hasMany(QuizQuestion::class, 'quiz_id', 'id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }
    
}
/*
alter table quiz_setting add column  created_by int(3) unsigned NOT NULL;
Since all previous PKT were conducted by user id 185 set created_by=185 for now;
update quiz_setting set created_by = 185;
*/