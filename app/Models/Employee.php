<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\Helper;

class Employee extends Model
{
    protected $fillable = ['name', 'empno', 'email', 'mobile', 'dob'];

    public function user()
    {
    	return $this->hasOne(User::class, 'emp_id', 'id');
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
