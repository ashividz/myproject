<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Registration;
use App\Models\Task;
use App\Models\TaskStep;
use App\Models\WorkflowStep;
use Auth;
use DB;

class RegistrationController extends Controller
{
    protected $workflow_id;

    public function __construct()
    {
        $this->workflow_id = 1;
    }
    public function store(Request $request)
    {
        $task = Task::where('lead_id', $request->id)
                ->where('workflow_id', $this->workflow_id)
                ->first();
        
        if($task)
        {
            return "Task already exists";
        }

        DB::transaction(function () use ($request) {

            //Create Task
            $task = new Task;
            $task->workflow_id = $this->workflow_id;
            $task->user_id = Auth::user()->id;
            $task->lead_id = $request->id;
            $task->remark = $request->remark;
            $task->save();

            //Fetch Step Id
            $step = WorkflowStep::where('workflow_id', $this->workflow_id)
                    ->orderBy('sortorder')
                    ->limit(1)
                    ->first();

            //Create Task Step
            $taskStep = new TaskStep;
            $taskStep->task_id = $task->id;
            $taskStep->step_id = $step->id;
            $taskStep->user_id = Auth::user()->id;
            $taskStep->state_id = 1;
            $taskStep->save();

            //Create Registration Process
            $registration = new Registration;
            $registration->task_id = $task->id;
            $registration->lead_id = $request->id;
            $registration->user_id = Auth::user()->id;
            $registration->amount = $request->amount;
            $registration->mode_id = $request->mode;
            $registration->duration = $request->duration;
            $registration->discount = $request->discount;
            $registration->remark = $request->remark;
            $registration->save();
        });

        dd($request);
    }
}
