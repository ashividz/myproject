<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class QualityController extends Controller
{
    protected $menu;

    public function __construct()
    {
    	$this->menu = "quality";
    }

    public function index()
    {
    	$data = array(
    		'menu'		=> $this->menu,
    		'section'	=> 'dashboard'
    	);

    	return view('home')->with($data);
    }
}
