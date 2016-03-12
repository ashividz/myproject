<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Lead;
use App\Models\Email;
use App\Models\EmailTemplate;
use Auth;

class EmailTemplateController extends Controller
{

    public function show(Request $request)
    {
            $templates = EmailTemplate::all();
            
            if(isset($request->template_id)){
                $template = EmailTemplate::findOrFail($request->template_id);       
            }
            else
                $template = null;
            
            $data = array(
                'template'      =>  $template,
                'templates'     =>  $templates,
                'template_id'   =>  $request->template_id,
            );

            return view('admin.email.template')->with($data);    
    }

    public function update(Request $request)
    {
        
        $template = EmailTemplate::findOrFail($request->template_id);
        $template->from     = $request->from;
        $template->subject  = $request->subject;
        $template->email    = $request->email;
        $template->sms      = $request->sms;
        $template->bulk     = isset($request->bulk) ? 1 : NULL;
        $template->save();

        return "Updated";
    }
}