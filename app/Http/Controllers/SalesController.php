<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use DB;

use App\Models\Lead;
use App\Models\User;
use App\Models\Status;

class SalesController extends Controller
{
    public $start_date;
    public $end_date;
    public $cre;
    public $url;
    private $menu;

    public function __construct()
    {
        $this->menu = 'sales';
        $daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($daterange[0]) ? date('Y/m/d 0:0:0', strtotime($daterange[0])) : date("Y/m/01 0:0:0");
        $this->end_date = isset($daterange[1]) ? date('Y/m/d 23:59:59', strtotime($daterange[1])) : date('Y/m/d 23:59:59');
        $this->cre = Auth::user()->employee->name;
        $this->url = env('CRM_LEAD_URL');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $data = array(
            'menu'      => 'sales',
            'section'   => 'index'
        );

        return view('home')->with($data);
    }

    public function viewHotPipelines()
    {

        $leads = Lead::getHotPipelines($this->start_date, $this->end_date);
        //dd($leads);

        $data = array(
            'menu'          => 'sales',
            'section'       => 'hot',
            'start_date'    => $this->start_date,
            'end_date'      => $this->end_date,
            'url'           => $this->url,
            'leads'     => $leads
        );

        return view('home')->with($data);
    }

    public function viewPayments()
    {
        $data = array(
            'menu'          => 'sales',
            'section'       => 'payments',
            'start_date'    => $this->start_date,
            'end_date'      => $this->end_date,
            'url'           => $this->url
        );
        return view('home')->with($data);
    }

    public function viewPipelineStatus()
    {
        $pipelines = array();
        $cres = User::getUsersByRole('cre');
        $statuses = Status::get();

        foreach ($cres as $cre) {
            $counts = Lead::select(DB::raw('status_id, count(*) AS cnt'))
                        ->join(DB::raw('(SELECT * FROM lead_cre A WHERE id = (SELECT MAX(id) FROM lead_cre B WHERE A.lead_id=B.lead_id)) AS c'), function($join) {
                            $join->on('marketing_details.id', '=', 'c.lead_id');
                        })
                        ->whereBetween('c.created_at', array($this->start_date, $this->end_date))
                        ->where('cre', $cre->name)
                        ->groupBy('status_id')
                        ->get();

            $pipeline['name'] = $cre->name;
            $pipeline['total'] = $counts->sum('cnt');
            $pipeline['counts'] = $counts;
            array_push($pipelines, $pipeline);
        }
        //dd($pipelines);

        $data   =   array(
            'menu'          =>  $this->menu,
            'section'       =>  'pipelines',
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'statuses'      =>  $statuses,
            'pipelines'     =>  json_encode($pipelines),
            'i'             =>  '0'
        );

        return view('home')->with($data);
    }
}
