<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\RoleUser;

class RoleUserController extends Controller
{
	/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request)
    {
        RoleUser::destroy($request->id);

        return "Deleted".$request->id;
    }
}