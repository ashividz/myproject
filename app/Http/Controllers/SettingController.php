<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function index()
    {
        $data = array(
            'menu'          => 'settings',
            'section'       => 'index'
        );

        return view('home')->with($data);
    }
}
