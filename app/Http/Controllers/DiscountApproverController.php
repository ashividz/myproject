<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\PaymentMethod;
use App\Models\Discount;
use App\Models\ApproverDiscount;
use App\Models\Role;

class DiscountApproverController extends Controller
{
    public function index($id)
    {
        $discount = Discount::with('approvers')
            ->find($id);

        $approvers = array_pluck($discount->approvers, 'id');

        $roles = Role::whereNotIn('id', $approvers)->get();

        $data = array(
            'discount'      =>  $discount,
            'roles'         =>  $roles
        );

        return view('settings.cart.modals.discount_approver')->with($data);
    }

    /*public function update(Request $request)
    { 
        $approver = ApproverDiscount::where('discount_id', $request->id)
                    ->where('approver_role_id', $request->value)
                    ->first();

        if ($approver) {
            return "Approver already exists";
        }

        $approver = ApproverDiscount::where('discount_id', $request->id)->first();
        $approver->approver_role_id =  $request->value;
        $approver->save();

        $role = Role::find($request->value);

        return $role->display_name;
    }*/

    public function store(Request $request, $id)
    { 
        $approver = new ApproverDiscount;
        
        $approver->discount_id =  $id; 
        $approver->approver_role_id =  $request->approver;
        $approver->save();

        $data = array(
            'message' => 'Discount Approver added', 
            'status' => 'success'
        );

        return back()->with($data);
    }

    public function destroy($id)
    { 
        $approver = ApproverDiscount::destroy($id);
        
        return 'Discount Approver deleted';
    }
}