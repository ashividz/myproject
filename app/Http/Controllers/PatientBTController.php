<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Requests\PatientBTRequest;

use App\Models\Patient;
use App\Models\PatientBT;

use Auth;
use DB;
use Session;

class PatientBTController extends Controller
{
   

    public function show($id)
    {
        //DB::update("UPDATE diet_assign AS f SET patient_id = (SELECT id FROM patient_details p WHERE p.clinic=f.clinic AND p.registration_no=f.registration_no) WHERE patient_id = 0");
        $patient = Patient::with('herbs', 'diets', 'suit')->find($id);
        //dd($patient);
         $bts = PatientBT::where('patient_id', $id)
                    ->orderBy('created_at', 'desc')
                    ->limit(12)
                    ->get();

        $data = array(
            'menu'          => 'patient',
            'section'       => 'partials.bt',
            'patient'       =>  $patient,
            'bts'         =>  $bts
        );
         return view('home')->with($data);   
    }

   
    public function edit($id)
    {
        $edit_bt = PatientBT::find($id);
        $patient = Patient::with('herbs', 'diets', 'suit')->find($edit_bt->patient_id);
        $bts = PatientBT::where('patient_id', $edit_bt->patient_id)
                    ->orderBy('created_at', 'desc')
                    ->limit(12)
                    ->get();

        $data = array(
            'menu'      => 'patient',
            'section'   => 'partials.bt_edit',
            'patient'   =>  $patient,
            'bts'       =>  $bts,
            'edit_bt'   =>  $edit_bt
        );

         return view('patient.partials.bt_edit')->with($data);
    }

   public function update(Request $request, $id)
    {  
        $patient_bt = PatientBT::find($id);
        $patient_bt->created_by = Auth::user()->id;
        $patient_bt->report_date = $request->report_date;
        $patient_bt->remark = $request->input('remark');
        if ($request->hasFile('bt_report')) {
            $f = $request->file('bt_report');
            $patient_bt->file_data = base64_encode(file_get_contents($f->getRealPath()));
            $patient_bt->mime = $f->getMimeType();
            $patient_bt->size = $f->getSize();
           }
        $patient_bt->save();
          //return('hughugj2');
        Session::flash('status', 'Report Updated Successfully!');
        return $this->edit($id);
    }

    public function upload(PatientBTRequest $request, $id)
    {
        if ($request->hasFile('bt_report')) {
            $f = $request->file('bt_report');
            $patient_bt = new PatientBT;
            $patient_bt->file_data = base64_encode(file_get_contents($f->getRealPath()));
            $patient_bt->patient_id = $id;
            $patient_bt->remark = $request->input('remark');
            $patient_bt->mime = $f->getMimeType(); //application/pdf
            $patient_bt->size = $f->getSize(); //20,00,000 B
            $patient_bt->created_by = Auth::user()->id;
            $patient_bt->report_date = $request->report_date;
            
            $patient_bt->save();
          }
          //return('hughugj2');
        return $this->show($id);
    }

    
    public function fetchBTReport($id)
    {
       $bt = PatientBT::find($id);
       $data = array(
            'bt' =>  $bt
        );

       /* $randomDir = md5(time() . $bt->id .  str_random());
        mkdir(public_path() . '/files/' . $randomDir);
        $path = public_path() . '/images/uploads/' . html_entity_decode('abc.pdf');
        file_put_contents($path, base64_decode($bt->file_data));*/
        /*return Response::make(base64_decode( $bt->file_data), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; '."blimey.pdf",
        ]);*/
        return view('patient.partials.bt_pdf')->with($data);  
    }

    
}