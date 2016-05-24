<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\EmailTemplate;

class SMSController extends Controller
{
    public function bulk()
    {
        $data = [
            'menu'      =>  'marketing',
            'section'   =>  'bulksms'
        ];

        return view('home')->with($data);
    }
}