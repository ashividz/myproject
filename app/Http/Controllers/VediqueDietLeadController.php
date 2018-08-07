<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon;
use Auth;

class VediqueDietLeadController extends Controller
{

	protected $menu;

	public function __construct(Request $request)
    {
		$this->menu = 'VediqueDiet';
    }

    public function viewLeads()
    {
          $leads = DB::connection('VediqueDiet')->table('leads')->get();

         // dd($leads);

          $data = array(
            'menu'          =>  $this->menu,
            'section'       =>  'lead',
            'leads'        =>  $leads,
            'i'             =>  '1'
        );

        return view('home')->with($data);
    }
}
