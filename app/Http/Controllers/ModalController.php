<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Lead;
use App\Models\Patient;
use App\Models\TaskAction;
use App\Models\User;
use App\Models\Fee;
use App\Models\Source;
use App\Models\PaymentMode;
use App\Models\Herb;
use App\Models\Mealtime;
use App\Models\Unit;
use Auth;

class ModalController extends Controller
{
    public function viewWorkflow(Request $request, $id)
    {
        $actions = TaskAction::where('workflow_id', $request->workflow)
                        ->get();

        $data = array(
            'id'        =>  $id,
            'actions'   =>  $actions, 
            );
        return view('modals/workflow')->with($data);
    }

    public function viewCre($id) {

        $cres = User::getUsersByRole('cre');
        
        $data = array(
            'cres'      =>  $cres,
            'id'        =>  $id,
        );

        return view('modals.cre')->with($data);   
    }

    public function viewBreakAdjust($id) {

        $fee = Fee::find($id);

        $data = array(
            'id'    =>  $id,
            'fee'   =>  $fee
        );

        return view('modals.break')->with($data);   
    }

    public function viewSource($id) {

        $sources = Source::get();

        $data = array(
            'id'        =>  $id,
            'sources'   =>  $sources
        );

        return view('modals.source')->with($data);   
    }

    public function viewRegistration($id) {

        $modes = PaymentMode::get();
        
        $lead = Lead::find($id);

        $data = array(
            'lead'      =>  $lead,
            'modes'     =>  $modes
        );

        return view('modals.registration')->with($data);   
    }

    public function payment($id) {

        $fee = Fee::find($id);

        $cres = User::getUsersByRole('cre');

        $sources = Source::get();

        $data = array(
            'fee'           =>  $fee,
            'sources'       =>  $sources,
            'cres'          =>  $cres
        );

        return view('modals.payment')->with($data);  
    }

    public function herb($id) {

        $herbs = Herb::orderBy('name')->get();
        $mealtimes = Mealtime::where('herb', 1)->get();
        $units = Unit::where('herb', 1)->get();

        $data = array(
            'herbs'         =>  $herbs,
            'mealtimes'     =>  $mealtimes,
            'units'         =>  $units,
            'id'            =>  $id
        );

        return view('modals.herb')->with($data);  
    }

    public function mealtime($id) {

        $mealtimes = Mealtime::where('herb', 1)->get();

        $data = array(
            'mealtimes'     =>  $mealtimes,
            'id'            =>  $id
        );

        return view('modals.mealtime')->with($data);  
    }

    public function mynutritionist($id) {

        $lead = Lead::find($id);
        $users = User::getUsers();

        $data = array(
            'lead'      =>  $lead,
            'id'        =>  $id,
            'users'     =>  $users
        );

        return view('modals.mynutritionist')->with($data); 
    }

    public function message($id) {

        $lead = Lead::find($id);
        $users = User::getUsers();

        $data = array(
            'lead'      =>  $lead,
            'users'     =>  $users,
            'id'        =>  $id
        );

        return view('lead.modals.message')->with($data); 
    }
}
