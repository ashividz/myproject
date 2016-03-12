<?php

namespace App\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class EmployeeSupervisor extends Model
{
    protected $table = "employee_supervisor";

    public function getDates()
    {
       return ['created_at', 'updated_at'];
    }

    public function employee()
    {
    	return $this->belongsTo(Employee::class, 'supervisor_employee_id');
    }
}