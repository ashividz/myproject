<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;


use Illuminate\Database\Eloquent\SoftDeletes;

use Auth;
use DB;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'emp_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)
                    ->whereNull('role_user.deleted_at')
                    ->withPivot('id');
    }

     public function replies()
    {
        return $this->hasMany(Reply::class, 'user_id');
    }

    public function hasRole($name, $requireAll = false)
    {
        if (is_array($name)) {
            foreach ($name as $roleName) {
                $hasRole = $this->hasRole($roleName);
                if ($hasRole && !$requireAll) {
                    return true;
                } elseif (!$hasRole && $requireAll) {
                    return false;
                }
            }
            // If we've made it this far and $requireAll is FALSE, then NONE of the roles were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the roles were found.
            // Return the value of $requireAll;
            return $requireAll;
        } else {
            foreach ($this->roles as $role) {
                if ($role->name == $name) {
                    return true;
                }
            }
        }
        return false;
    }

    public function isApprover($step_id)
    {
        $array = array();

        $roles = Auth::user()->roles()->get();
        //User::with('roles')
                   // ->find(Auth::user()->id);

        //;//
        foreach ($roles as $role) {
            array_push($array, $role->id);
        }
        //dd($array);

        $val = WorkflowStepApprover::where('step_id', $step_id)
                    ->whereIn('role_id', $array)
                    ->first();
                    //dd($val);

        if (isset($val->id)) {
            return $val->id ."Hero";
        }

        return "sfd";
        
    }


    public static function getUsers()
    {
        return User::join('employees AS e', 'e.id', '=', 'emp_id')
                ->join('role_user AS ru', 'users.id', '=', 'user_id')
                ->orderBy('e.name')
                ->select('users.id', 'e.name')
                ->get();
    }

    public static function getUsersWithEmployee()
    {
        return User::join('employees AS e', 'e.id', '=', 'emp_id')                
                ->orderBy('e.name')
                ->select('users.id', 'e.name')
                ->get();   
    }

    public static function getUsersByRole($role, $user_id = null)
    {
        $users =  User::join('employees AS e', 'e.id', '=', 'emp_id')
                ->join('role_user AS ru', 'users.id', '=', 'user_id')
                ->join('roles AS r', 'r.id', '=', 'ru.role_id');

        if(Auth::user()->hasRole('sales_tl')  || $user_id) {
            if ($user_id) {
                $user = User::find($user_id);
                if ($user) {
                    $user_id = $user->emp_id;
                }
            } else {
                $user_id = Auth::user()->emp_id;
            }

            $users = $users->join(DB::raw('(SELECT * FROM employee_supervisor A WHERE id = (SELECT MAX(id) FROM employee_supervisor B WHERE A.employee_id=B.employee_id)) AS s'), function($join) {
                        $join->on('e.id', '=', 's.employee_id');
                    })
                ->where('s.supervisor_employee_id', $user_id);//
        }

        $users = $users->where('r.name', $role)
                ->orderBy('e.name')
                ->groupBy('users.id')
                ->select('users.id', 'e.name');

        return $users->get();
    }

    public static function getNamesByRole($role)
    {
        return User::join('employees AS e', 'e.id', '=', 'emp_id')
                ->join('role_user AS ru', 'users.id', '=', 'user_id')
                ->join('roles AS r', 'r.id', '=', 'ru.role_id')
                ->where('r.name', $role)
                ->orderBy('e.name')
                ->groupBy('users.id')
                ->select('e.name')
                ->get();
    }
}
