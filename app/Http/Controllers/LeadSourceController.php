<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use App\Models\Employee;
use App\Models\Patient;
use App\Models\Disposition;

use App\Models\Source;
use App\Models\Status;

use DB;
use Auth;

class LeadSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

      public function __construct(Request $request)
    {   
        $this->limit = isset($request->limit) ? $request->limit : 1000;
       
        $this->cre = isset($request->user) ? $request->user : Auth::user()->employee->name;
        $this->daterange = isset($_REQUEST['daterange']) ? explode("-", $_REQUEST['daterange']) : "";

        $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y-01-01 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y-m-d 23:59:59');
        
    }

     public function sourceLeads($source_id, Request $request)
    {
        $users = User::getUsersByRole('cre');
        $statuses = Status::get();
        $dispositions = Disposition::get();
        $cre  = $request->user;
        $source_selected = $request->source;
        $status_id  = $request->status;
        $disposition_id  = $request->disposition;

        $sources = Source::get();
       if(isset($source_selected))
            $source_id = $source_selected;
        else
            $source_selected = $source_id;
        $leads_query = Lead::with('sources.master', 'status')
                    
                        /*->join(DB::raw("(SELECT * FROM lead_source WHERE source_id = '$source_id') AS ls1"), function($join) {
                                 $join->on('marketing_details.id', '=', 'ls1.lead_id');
                            })*/
                     

                         /*->join(DB::raw("(SELECT * FROM lead_sources ls1 WHERE source_id = '$source_id' and id = (SELECT MAX(id) FROM lead_sources ls2 WHERE ls1.lead_id=ls2.lead_id)) AS ls"), function($join) {
                                 $join->on('marketing_details.id', '=', 'ls.lead_id');
                            });*/

                        ->join(DB::raw("(SELECT * FROM lead_sources ls1 WHERE source_id='$source_id' and (created_at BETWEEN '$this->start_date' and '$this->end_date') and id = (SELECT MAX(id) FROM lead_sources ls2 WHERE ls1.lead_id=ls2.lead_id)) AS ls"), function($join) {
                                     $join->on('marketing_details.id', '=', 'ls.lead_id');
                                   });

                          if(!empty($disposition_id))
                                $leads_query->join(DB::raw("(SELECT * FROM call_dispositions cd1 WHERE  disposition_id ='1' and id = (SELECT MAX(id) FROM call_dispositions cd2 WHERE cd1.lead_id=cd2.lead_id)) AS d"), function($join) {
                                     $join->on('marketing_details.id', '=', 'd.lead_id');
                                         
                                });
                            
                          if(!empty($cre))
                                $leads_query->leftjoin(DB::raw("(SELECT * FROM lead_cre A WHERE (cre = '$cre') and (deleted_at IS NULL OR deleted_at = '') and (created_at BETWEEN '$this->start_date' and '$this->end_date') and id = (SELECT MAX(id) FROM lead_cre B WHERE A.lead_id=B.lead_id)) AS c"), function($join) {
                                     $join->on('marketing_details.id', '=', 'c.lead_id');
                                });
                          else
                                $leads_query->leftjoin(DB::raw("(SELECT * FROM lead_cre A WHERE (deleted_at IS NULL OR deleted_at = '') and (created_at BETWEEN '$this->start_date' and '$this->end_date') and id = (SELECT MAX(id) FROM lead_cre B WHERE A.lead_id=B.lead_id)) AS c"), function($join) {
                                     $join->on('marketing_details.id', '=', 'c.lead_id');
                                });

                    


                    if(!empty($status_id))
                    
                        $leads_query->where('status_id', '=', $status_id);
                
                     

                    $leads_query->orderBy('c.created_at', 'DESC');

                    $leads_query->SELECT('marketing_details.*');

        $leads =  $leads_query->limit($this->limit)->get();
              //dd($leads);      

        $data = array(
            'menu'          =>  'reports',
            'section'       =>  'source_leads',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'users'         =>  $users,
            'name'          =>  $cre,
            'statuses'      =>  $statuses,
            'status_id'     =>  $status_id,
            'dispositions'  =>  $dispositions,
            'disposition_id'=>  $disposition_id,
            'leads'         =>  $leads,
            'sources'       =>  $sources,
            'source_selected'=>  $source_selected,
            'limit'         =>  $this->limit
        );

        return view('home')->with($data);
    }


    
}