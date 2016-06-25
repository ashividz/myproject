<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\IPRole;
use App\Models\Role;

class IPController extends Controller
{
    public function __construct()
    {
        $this->menu = "admin";
    }

    public function viewIPRoles()
    {
        $ipRoles = IPRole::all();
        $roles   = Role::all();
        $data    = array(
            'menu'      =>  $this->menu,
            'section'   =>  'ip.index',
            'ipRoles'   =>  $ipRoles,
            'roles'     =>  $roles,
        );

        return view('home')->with($data);
    }
    
    public function saveIPRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ip_start' => 'required|ip',
            'ip_end'   => 'required|ip',
            'role_id'  => 'required'
        ]);
        if ($validator->fails()) {
            return back()
                    ->withErrors($validator);
        }        
        
        $validator->after(function($validator) use ($request){
            if (trim($request->ip_end) =='' && !filter_var($request->ip_start, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $validator->errors()->add('ip_start', 'This is not a valid ipv4 address');
            }            
            elseif ( !(filter_var($request->ip_start, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && filter_var($request->ip_end, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) ) {
                $validator->errors()->add('ip_address', 'Please enter a valid ipv4 address');
            }            
            else if (!( ip2long($request->ip_start) <= ip2long($request->ip_end)) )  {
                $validator->errors()->add('ip_address', 'minimum ip range should be less than or equal to maximum ip range');
            }            
        });

        if ($validator->fails()) {
            return back()
                    ->withErrors($validator);
        }

        if ( trim($request->ip_end) =='' )
        $request->merge([            
            'ip_end'       => $request->ip_start,             
        ]);            
        
        $request->merge([
            'created_by'       => Auth::id(),             
            'updated_by'       => Auth::id(),             
        ]);        
        
        IPRole::create($request->all());        
        return back();
    }

    public function deleteIPRole(Request $request)
    {
        if(IPRole::destroy($request->id))
        {
            return "IP range deleted successfully";
        }

        return "Error";
    }
}