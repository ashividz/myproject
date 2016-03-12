<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskStep extends Model
{
    public function task()
    {
    	$this->belongTo(Task::class);
    }

    public function state()
    {
    	return $this->hasOne(WorkflowState::class, 'id', 'state_id');
    }

    public function step()
    {
    	return $this->hasOne(WorkflowStep::class, 'id', 'step_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
