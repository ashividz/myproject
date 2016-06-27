<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Support\Helper;
use App\Models\Employee;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use Auth;
use DB;
use Carbon;

class UserController extends Controller
{
    private $menu;

    public function __construct()
    {
        $this->menu = "admin";
    } 

    /**
     * Store a newly created User.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request, $id)
    {
        try 
        {
            //$employee = Employee::find($id);
            
            //dd($employee);
            $user = User:: where(['emp_id' => $id])->first();

            if (!$user) {
                $user = new User;
            }
            
            $user->emp_id = $request->emp_id;
            $user->username = trim($request->username);
            $user->email = Helper::emptyStringToNull($request->email);
            $user->password = bcrypt($request->password);
            $user->created_by = Auth::user()->employee->name;
            $user->save();
            
        } 
        catch (Exception $e) 
        {
            $message = $e;
        }
            
        /*$users = DB::table('employee as e')
                    ->join('users as u', 'u.emp_id', '=', 'e.id')
                    ->get('e.id', 'e.name', 'u.email');
        $data = array(
            'menu'      => 'admin',
            'section'   => 'users',
            'message'   => $message,
            'users'     => $users
        );

        return view('home')->with($data);*/
        return redirect('/admin/employees');
    }

    public function showPasswordForm()
    {
        $data = array(
            'menu'      => 'auth',
            'section'   => 'change',
        );

        return view('home')->with($data);
    }

    public function changePassword(Request $request)
    {
        $password = $request->input('password');

        try {
            
            User::find(Auth::id())
                ->update(['password' => bcrypt($password)]);
            
            $message = 'Password Successfully Changed!';
            
        } 
        catch (Exception $e) 
        {
            $message = $e;
        }
        
        //$user->password = bcrypt("Passw0rd");
        //$user->save();
        
        $data = array(
            'menu'      => $this->menu,
            'section'   => 'dashboard',
            'message'   => $message
        );

        return view('home')->with($data);
    }

    

    public function showEditForm($id)
    {
        $user = DB::table('employees as e')
                    ->leftjoin('users as u', 'u.emp_id', '=', 'e.id')
                    ->where('e.id', '=', $id)
                    //->select('e.id', 'e.name', 'u.email')
                    ->first();

        $data = array(
            "menu"      => "admin",
            "section"   => "editUser",
            "user" => $user
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
        User::where('emp_id', $id)
                ->update([
                    'username'     => $request['username'],
                    'email'    => Helper::emptyStringToNull($request['email']),
                    'mobile'   => Helper::emptyStringToNull($request['mobile'])
                ]);

        if ($request['password']) {
           User::where('emp_id', $id)
                ->update([
                    'password'   => bcrypt($request['password'])
                ]);
        }

        return redirect('admin/viewUsers');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function viewUsers()
    {
        $employees = Employee::with('user')->get();
        /*DB::table('employees as e')
                    ->leftjoin('users as u', 'u.emp_id', '=', 'e.id')
                    ->select('e.id AS emp_id', 'e.name', 'u.id', 'u.username', 'u.email')
                    ->get();*/


        $data = array(
            'menu'      => $this->menu,
            'section'   => "viewUsers",
            'employees'     => $employees
        );
        return view('home')->with($data);
    }


    public function viewRole($id)
    {
        $roles = Role::get();

        $user = User::with('roles')
                ->find($id);


        $data = array(
            'id'        =>  $id,
            'menu'      =>  $this->menu,
            'section'   =>  'viewRole',
            'user'      =>  $user,
            'roles'     =>  $roles
        );
        //dd($user);

        return view('home')->with($data);
    }

    public function addRole(Request $request, $id)
    {
        $user  = User::find($id);

        $role = Role::find($request->role);

        if (!$user->hasRole($role->name)) {
           $role_user = new RoleUser;
           $role_user->user_id = $id;
           $role_user->role_id =  $request->role;
           $role_user->save();
        }

        return $this->viewRole($id);
    }

    public function toggledelete(Request $request)
    {
        $user = User::withTrashed()->find($request->id);

        if ($user && $user->deleted_at) {

            $user->deleted_at = null;
            $user->save();

        } else {
            $user->delete();
        }

        return $user->deleted_at;
    }
}
