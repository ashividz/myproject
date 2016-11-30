<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;



class TestimonialController extends Controller
{

	public function show()
	{
		$data = array(
			'menu'		=>	'common',
			'section'	=>	'testimonial'
		);


		return view('home')->with($data);
	}

}