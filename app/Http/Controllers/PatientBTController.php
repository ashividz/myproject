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
use App\Models\User;

use Auth;
use DB;
use Session;

class PatientBTController extends Controller
{
    public function __construct(Request $request)
    {   
        $this->limit = isset($request->limit) ? $request->limit : 1000;
        $this->cre = isset($request->user) ? $request->user : Auth::user()->employee->name;
        $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
        $this->start_date = isset($this->daterange[0]) ? date('Y-m-d 0:0:0', strtotime($this->daterange[0])) : date("Y-m-01 0:0:0");
        $this->end_date = isset($this->daterange[1]) ? date('Y-m-d 23:59:59', strtotime($this->daterange[1])) : date('Y-m-d 23:59:59');
        
    }
>>>>>>> 180ed454bcac3922fbc29fc6372f3d75313f9345

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
<<<<<<< HEAD
        mkdir(public_path() . '/files/' . $randomDir);
        $path = public_path() . '/images/uploads/' . html_entity_decode('abc.pdf');
=======
        mkdir(public_path() . '/files/' . $randomDir); */
        /*$path = public_path() . '/images/uploads/' . html_entity_decode('abc.pdf');
>>>>>>> 180ed454bcac3922fbc29fc6372f3d75313f9345
        file_put_contents($path, base64_decode($bt->file_data));*/
        /*return Response::make(base64_decode( $bt->file_data), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; '."blimey.pdf",
        ]);*/
        return view('patient.partials.bt_pdf')->with($data);  
    }

    public function groupBTReport(Request $request)
    {
       $users = User::getUsersByRole('nutritionist');
       $patients = Patient::with('prakritis','fee','weight','weights','herbs','rh_factor','blood_type')->join(DB::raw("(select distinct patient_id,entry_date from fees_details where (entry_date between '$this->start_date' and '$this->end_date')) AS fd"), function($join) {
                                 $join->on('patient_details.id', '=', 'fd.patient_id');
                            })->select('patient_details.*','fd.entry_date as entry_date')->orderBy('fd.entry_date','asc')->get();
       //dd($patients);
       //$patients = Patient::getPatientsWithTestimonial();
       $data = array(
            'menu'          => 'reports',
            'section'       => 'patients.btReport',
            'users'         =>  $users,
            'patients'      =>  $patients,
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
        );
         return view('home')->with($data); 
    }
}