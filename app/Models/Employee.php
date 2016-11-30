<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\Helper;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['name', 'empno', 'email', 'mobile', 'dob'];

    public function user()
    {
    	return $this->hasOne(User::class, 'emp_id', 'id');
    }

    public function titles()
    {
        return $this->belongsToMany(Title::class)->withPivot('start_date', 'end_date');
    }

    public function sup()
    {
        return $this->belongsToMany(Employee::class, 'employee_supervisor', 'employee_id', 'supervisor_employee_id');
    }

    public function supervisor()
    {
        return $this->hasOne(EmployeeSupervisor::class, 'employee_id')->latest();
    }

    public function supervisors()
    {
        return $this->hasMany(EmployeeSupervisor::class, 'employee_id')->orderBy('id', 'desc');
    }


    public static function updateEmployee($id, $request)
    {
    	$employee = Employee::find($id);

    	$employee->name = isset($request->name) ? Helper::properCase($request->name) : Helper::properCase($employee->name);
    	$employee->email = isset($request->email) ? trim($request->email) : $employee->email;
    	$employee->mobile = isset($request->mobile) ? $request->mobile : $employee->mobile;
    	$employee->dob = isset($request->dob) ? $request->dob : $employee->dob;
    	$employee->save();
    } 
}
