<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;

use App\Models\Workflow;
use App\Models\WorkflowStep;
use App\Models\Task;
use App\Models\TaskAction;
use App\Models\TaskStep;

class WorkflowController extends Controller
{
    protected $daterange;
    protected $start_date;
    protected $end_date;

    public function __construct(Request $request)
    {
    
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y/m/01 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y/m/d 23:59:59');
        
    } 

    public function viewWorkflow($id)
    {
        $workflow = Workflow::findorFail($id);

        $tasks = Task::where('workflow_id', '=', $id)
                        ->with('steps')
                        ->with('user')
                        ->with('lead')
                        ->with('registration')
                        ->whereBetween('created_at', array($this->start_date, $this->end_date))
                        ->get();

        $steps = WorkflowStep::where('workflow_id', '=', $id)
                    ->orderBy('sortorder')
                    ->get();

        $count = WorkflowStep::where('workflow_id', '=', $id)->count();

        $data = array(
            'workflow'      =>  $workflow,
            'steps'         =>  $steps,
            'tasks'         =>  $tasks,
            'count'         =>  $count,
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date
        );

        return view('workflow')->with($data);
    }

    

    public function updateModal(Request $request)
    {

        
        //Update Current Task Step
        $action = TaskAction::find($request->status);
        $task = TaskStep::find($request->id);

        $task->state_id = $action->state_id;
        $task->remark = $request->remark;
        $task->save();

        //Move to next Task Step if current Step Completed & Next Task Step Exisits
        $currentStep = WorkflowStep::find($task->step_id);
        
        $nextStep = WorkflowStep::where('workflow_id', $action->workflow_id)
                        ->where('sortorder', $currentStep->sortorder + 1)
                        ->first();
        

        if ($action->state_id == 3 && isset($nextStep->id)) {
            try {
                $newStep = new TaskStep;
                $newStep->task_id = $task->task_id;
                $newStep->step_id = $nextStep->id;
                $newStep->state_id = 1;
                $newStep->user_id = Auth::user()->id;
                $newStep->save();
                
            } catch (\Illuminate\Database\QueryException $e) {
                return $e;
            }

            return "Task Updated";
        }
        elseif($action->state_id == 3) {
            //$task = Task::find($task->task_id);
            return "Task Complete";
        }
        else
        {
            return "Task Denied";
        }

        
    }
}