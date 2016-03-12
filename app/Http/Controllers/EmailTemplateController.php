<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Lead;
use App\Models\Email;
use App\Models\EmailTemplate;
use App\Models\EmailAttachment;
use Auth;
use Session;

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

    public function getAttachment($id, Request $request=null)
    { 
        
      $attachment_name = ($request)?$request->attachment_name:null;
      $attachment = EmailAttachment::find($id);
       $data = array(
                'attachment_name'      =>  $attachment_name,
               
            );

        //$randomDir = md5(time() . $bt->id .  str_random());
        //mkdir(public_path() . '/files/' . $randomDir);
       

        
        /*return Response::make(base64_decode( $bt->file_data), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; '."blimey.pdf",
        ]);*/

      return view('admin.email.editAttachment')->with($data);  

    }

      public function updateAttachment($id, Request $request)
    { 
        $attachment = EmailAttachment::find($id);
        //$attachment->created_by = Auth::user()->id;
       
        
        if ($request->hasFile('email_attachment')) {
            $f = $request->file('email_attachment');
            $attachment->file = base64_encode(file_get_contents($f->getRealPath()));
           
           }
        $attachment->save();
          //return('hughugj2');
        Session::flash('status', 'Attachment Updated Successfully!');
        return $this->getAttachment($id);   

    }


     public function showAttachment($id, Request $request)
    { 
      $attachment = EmailAttachment::find($id);
       $data = array(
            'attachment' =>  $attachment
        );
        $path = public_path() . '/images/uploads/' . html_entity_decode('temp_attachment.pdf');
        file_put_contents($path, base64_decode($attachment->file));
      
        return view('admin.email.attachmentPdf')->with($data);   

    }
}