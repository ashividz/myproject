<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\CreateCartRequest;

use App\Models\Cart;
use App\Models\CartStatus;
use App\Models\Role;
use App\Models\CartApprover;

class CartApproverController extends Controller
{
    public function modal($id)
    {
        $status = CartStatus::find($id);

        $roles = Role::get();

        $data = array(
            'menu'          => 'settings',
            'section'       => 'cart.status',
            'status'        =>  $status,
            'roles'         =>  $roles
        );

        return view('settings.cart.modals.status_approver')->with($data);
    }

    public function store(Request $request, $id)
    {
        $this->validate($request, [
            'approver_role_id'      => 'required',
            'status_id'             => 'required'
        ]);

        $approver = new CartApprover;
        $approver->create($request->all());

        $data = array(
            'message'   => 'Cart Approver added', 
            'status'    => 'success'
        );

        return back()->with($data);
    }

    public function delete($id)
    {
        $method = CartApprover::destroy($id);

        return "Payment Method deleted";
    }
}