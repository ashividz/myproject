<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\PaymentMethod;
use App\Models\ApproverPayment;
use App\Models\Role;

class PaymentApproverController extends Controller
{
    public function index($id)
    {
        $method = PaymentMethod::with('approvers')->find($id);

        $approvers = array_pluck($method->approvers, 'id');

        $roles = Role::whereNotIn('id', $approvers)->get();

        $data = array(
            'method'        =>  $method,
            'roles'         =>  $roles,
            'i'             => 1
        );

        return view('settings.cart.modals.payment_method_approver')->with($data);
    }

    /*public function update(Request $request)
    { 
        $approver = ApproverPayment::where('payment_method_id', $request->id)->first();
        $approver->approver_role_id =  $request->value;
        $approver->save();

        $role = Role::find($request->value);

        return $role->display_name;
    }
*/


    public function delete($id)
    {
        $method = ApproverPayment::destroy($id);

        return "Payment Method deleted";
    }

    public function store(Request $request, $id)
    { 
        $approver = new ApproverPayment;
        
        $approver->payment_method_id =  $id; 
        $approver->approver_role_id =  $request->approver;
        $approver->save();

        $data = array(
            'message' => 'Approver added', 
            'status' => 'success'
        );

        return back()->with($data);
    }
}