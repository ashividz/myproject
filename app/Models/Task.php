<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Task extends Model
{
    public function steps()
    {
		return $this->hasMany(TaskStep::class, 'task_id')
					->join('workflow_steps AS ws', 'ws.id', '=', 'step_id')
					->select('task_steps.*')
					->orderBy('sortorder');
    }

    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function lead()
    {
    	return $this->belongsTo(Leads::class, 'lead_id');
    }

    public function registration()
    {
        return $this->hasOne(Registration::class);
    }
}
