<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use App\Support\Helper;
use App\Models\Employee;
use App\Models\EmployeeSupervisor;
use App\Models\User;
use Input;
use Auth;
use DB;

class EmployeeController extends Controller
{
    
    public function index()
    {
        $data = array(
            "menu"      => "admin",
            "section"   => 'employee.index'
        );

        return view('home')->with($data);
    }

    public function get()
    {
        return Employee::with(['user' => function($q) {
                    $q->withTrashed()->with('roles');
                }])
                ->orderBy('name')
                ->get();
    }

    public function show()
    {
        /**/
        $username = Auth::user()->username;
        $user = DB::table('users as u')
                        ->join('employees as e', 'e.id', '=', 'u.emp_id')
                        ->first();

        $data = array(
            "menu"      => "partials",
            "section"   => "profile",
            "user"      => $user
        );

        return view('home')->with($data);
    }

    public function showRegistrationForm()
    {
        $data = array(
            'menu' => "hr",
            'section' => "registerEmployee"
        );
        return view('home')->with($data);
    }

    public function register(Request $request)
    {
        try {
            
            $employee = new Employee;
            $employee->name = $request->name;
            $employee->emp_no = $request->emp_no;
            $employee->email = Helper::emptyStringToNull($request->email);
            $employee->mobile = Helper::emptyStringToNull($request->mobile);
            $employee->dob = Helper::emptyStringToNull($request->dob);
            $employee->created_by = Auth::user()->employee->name;
            $employee->save();
            $errors = "";
            
        } catch (Exception $e) {
            $errors = $e;   
        }
        
        return redirect('hr/employees');
    }


    public function showEditForm($id)
    {
        $employee = Employee::where('id', $id)->first();

        $data = array(
            "menu"      => "hr",
            "section"   => "editEmployee",
            "employee" => $employee
        );

        return view('home')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        Employee::where('id', $id)
                ->update([
                    'name'     => $request['name'],
                    'emp_no'       => $request['emp_no'],
                    'email'    => Helper::emptyStringToNull($request['email']),
                    'mobile'   => Helper::emptyStringToNull($request['mobile']),
                    'dob'      => Helper::emptyStringToNull($request['dob'])
                ]);

        return redirect('hr/employees');
    }

    public function personalDetails(Request $request, $id) {

        if (count(Input::all())) {
           
           Employee::updateEmployee($id, $request);
        }

        $employee = Employee::find($id);

        $data = array(
            'menu'          =>  'hr',
            'section'       =>  'partials.personal',
            'employee'      =>  $employee
        );

        return view('home')->with($data);  
    }

    public function contactDetails(Request $request, $id) {

        if (count(Input::all())) {
           
           Employee::updateEmployee($id, $request);
        }

        $employee = Employee::find($id);

        $data = array(
            'menu'          =>  'hr',
            'section'       =>  'partials.contact',
            'employee'      =>  $employee
        );

        return view('home')->with($data);  
    }

    public function viewPhotograph($id) {

        $employee = Employee::find($id);

        $data = array(
            'menu'          =>  'hr',
            'section'       =>  'partials.photograph',
            'employee'      =>  $employee
        );

        return view('home')->with($data);  
    }

    public function supervisors($id)
    {
        $employee = Employee::with('supervisors.employee')->find($id);

        $employees = Employee::orderBy('name')->get();

        //dd($employee);

        $data = array(
            'menu'          => 'employee',
            'section'       => 'partials.supervisor',
            'employee'      =>  $employee,
            'employees'     =>  $employees,
            'i'             =>  1
        );

        return view('home')->with($data);
    }

    public function addSupervisor(Request $request, $id)
    {
        //Check if supervisor and employee is same
        if($id == $request->supervisor) {
            return "Cannot add self as Supervisor";
        }

        //Check if same supervisor
        $supervisor = EmployeeSupervisor::where('employee_id', $id)->orderBy('id', 'desc')->first();

        if($supervisor) {
            if($supervisor->supervisor_employee_id == $request->supervisor) {
                return "Cannot add same Supervisor";
            }

            //Check another supervisor on same date
            if($supervisor->created_at->format('Y-m-d') == date('Y-m-d')) {
                return "Cannot add another Supervisor on same date";
            }
        }
        
        $supervisor = new EmployeeSupervisor;
        $supervisor->employee_id = $id;
        $supervisor->supervisor_employee_id = $request->supervisor;
        $supervisor->created_by = Auth::user()->employee->name;
        $supervisor->save();

        return "Supervisor saved";
    }

    public function showUserRegistrationForm($id)
    {
        $employee = Employee::find($id);

        $user = User::where('emp_id', $id)->first();

        $data = array(
            'menu'      => 'admin',
            'section'   => 'addUser',
            'employee'    => $employee,
            'user'      => $user
        );

        return view('home')->with($data);
    }
}
