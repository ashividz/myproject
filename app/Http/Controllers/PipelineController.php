<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Lead;
use App\Models\User;
use App\Models\Pipeline;
use App\Models\LeadStatus;
use App\Models\CallDisposition;

use Carbon;
use Auth;

class PipelineController extends Controller
{
    public function index()
    {
        $data = array(
            'menu'      => 'sales',
            'section'   =>  'reports.pipelines'
        );

        return view('home')->with($data);
    }

    public function modal($id)
    {
        $lead = Lead::find($id);

        $data = array(
            'lead'   => $lead
        );

        return view('lead.modals.pipeline')->with($data);
    }

    public function store(Request $request, $id)
    {
        //dd($request);
        $lead = Lead::find($id);

        if ($lead) {
            $pipeline = new Pipeline;
            $pipeline->create($request->all());

            $status = LeadStatus::saveStatus($lead, 4);

            $disposition = new CallDisposition;
            $disposition->create([
                'lead_id'           => $lead->id, 
                'disposition_id'    => 15,
                'name'              => Auth::user()->employee->name,
                'remarks'           =>  'Hot Pipeline Created. '. $request->remark
            ]);

            $data = array(
                'message'       => 'Hot Pipeline created',
                'status'        =>  'success'
            );

            return back()->with($data);
        }

        return "Lead does not exist";
    }

    public function hotPipelines(Request $request)
    {   
        $start_date = $request->start_date ? $request->start_date : Carbon::now()->format('Y-m-d'); 
        $end_date = $request->end_date ? $request->end_date : Carbon::now()->format('Y-m-d');
        $cres = User::getUsersByRole('cre', $request->user_id);

        foreach ($cres as $cre) {
            $pipelines = Pipeline::with('lead', 'currency', 'state')
                        ->whereBetween('date', array($start_date, $end_date))
                        ->where('created_by', $cre->id)
                        ->get();
            $cre->pipelines = $pipelines;
        }

        return $cres;
    }
}
