<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Lead;
use App\Models\Status;
use App\Models\Cart;

use Carbon;
use DB;

class SalesReportController extends Controller
{   
    protected $start_date;
    protected $end_date;

    public function __construct(Request $request)
    {
        $this->start_date = $request->start_date ? $request->start_date : Carbon::now()->format('Y-m-d'); 
        $this->end_date = $request->end_date ? $request->end_date : Carbon::now();

    }

    public function viewLeadStatus()
    {
        $data = array(
            'menu'          =>  'sales',
            'section'       =>  'reports.lead_status'
        );

        return view('home')->with($data);
    }

    public function leadStatusReport(Request $request)
    {
        $start_date = $request->start_date ? $request->start_date : Carbon::now()->subDays(30); 
        $end_date = $this->end_date;

        //echo $start_date . " - ". $end_date."<br>";
        $cres = User::getUsersByRole('cre', $request->user_id);
        foreach ($cres as $cre) {
            $leads = Lead::with(['dispositions' => function($q) use($cre) {
                        $q->where('name', 'like', $cre->name);
                    }])
                    ->where('cre_name', $cre->name)
                    ->whereBetween('created_at', array($start_date, $end_date))
                    ->get(); 

            $cre->leads = $leads->count();

            $last = 0;
            $calls = 0;
            $never = 0;
            foreach ($leads as $lead) {
                if (!$lead->dispositions->isEmpty()) {
                    
                    if ($lead->dispositions->last()->created_at <= Carbon::now()->subDays(4)) {
                        $last++;
                    }
                    if ($lead->dispositions->count() < 4 ) {
                        $calls++;
                    }
                } else {
                    $never++;
                }
            }

            $cre->last = $last;
            $cre->calls = $calls;
            $cre->never = $never;
            $counts = Status::select(DB::raw('m_lead_status.id, count(m.id) as cnt'))
                        ->leftJoin('marketing_details as m', function($join) use($start_date, $end_date, $cre) {
                            $join->on('m_lead_status.id', '=', 'm.status_id')
                            ->where('created_at', '>=', $start_date)
                            ->where('created_at', '<=', $end_date)
                            ->where('cre_name', 'like', $cre->name);
                        })
                        ->groupBy('m_lead_status.id')                        
                        ->get();

            $cre->counts = $counts;

            /*try {

                $dialer_dispositions = DB::connection('pgsql')->table('ct_recording_log as crl')
                            ->where('crl.phonenumber', '=', $lead->phone);
            } catch (\Exception $e) {
            
                Session::flash("message", "Error connecting with Dialer Database");
                Session::flash("status", "error");
            }   */  
        }

        //dd($cres);
        return $cres;
    }

    public function performance()
    {
        $data = [
            'menu'      => 'reports',
            'section'   =>  'sales.performance'
        ];

        return view('home')->with($data);
    }

    
}
