<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Lead;

use Carbon;
use Auth;

class CreReportController extends Controller
{
    public function leads(Request $request, $id)
    {
        $user = User::find($id);
        $start_date = $request->start_date ? $request->start_date : Carbon::now()->subDays(30); 
        $end_date = $request->end_date ? $request->end_date : Carbon::now();

        $query = Lead::with('source.master', 'cre', 'status', 'disposition')
                    ->with(['dispositions' => function($q) use($user) {
                        $q->where('name', 'like', $user->employee->name);
                    }])
                    ->where('cre_name', $user->employee->name)
                    ->whereBetween('created_at', array($start_date, $end_date));
        if ($request->status) {
            $query = $query->where('status_id', $request->status);
        }

        
        
        $leads = $query->get(); 

        $leads = $leads->keyBy('id');

        if (isset($request->never)) {
            foreach ($leads as $lead) {
                if (!$lead->dispositions->isEmpty()) {
                   $leads->forget($lead->id);
                }
            }
        }

        $data = array(
            'leads'     =>  $leads,
            'i'         =>  1
        );

        return view('sales.modal.leads')->with($data);
    }
}
