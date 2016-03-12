<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Role;

class RoleController extends Controller
{
    
    public function viewUserRoles()
    {
        $roles = Role::all();

        $data = array(
            'menu'      => 'admin',
            'section'   => 'viewUserRoles',
            'roles'     =>  $roles
        );

        return view('home')->with($data);
    }

    public function viewAddUserRole()
    {

        $data = array(
            'menu'      => 'admin',
            'section'   => 'addUserRole'
        );

        return view('home')->with($data);
    }

    public function addUserRole(Request $request)
    {
        $role = new Role;
        $role->name = $request->name;
        $role->display_name = $request->display_name;
        $role->description = $request->description;
        $role->save();

        $roles = Role::all();

        $data = array(
            'menu'      => 'admin',
            'section'   => 'viewUserRoles',
            'roles'     =>  $roles
        );

        return view('home')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */

    public function getRoles() //For Editable
    {
        $json_array = array();

        $roles =  Role::all();        

        foreach ($roles as $role) {
            $json_array[$role->id] = $role->display_name;
        }

        return json_encode($json_array);
    }
}
