<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Leads;
use App\Models\Lead;
use App\Models\Patient;
use App\Models\Fee;
use App\Models\Diet;
use App\Models\LeadSource;
use App\Models\LeadCre;
use App\Models\LeadStatus;
use App\Models\Source;
use App\Models\Voice;
use App\Models\CallDisposition;
use App\Models\OBD;
use App\Models\Cod;
use App\Models\LeadDnc;
use App\Models\City;
use App\Models\User;

use DB;
use Auth;
use App\Support\Helper;
use Redirect;
use Session;
use App\DND;

class LeadController extends Controller
{
    protected $menu;
    protected $dialer_url;

    public function __construct()
    {
        $this->menu = 'lead';

        $this->dialer_url = env('DIALER_URL');
    }

    public function index()
    {
        $data = array(
            'menu'      => $this->menu,  
            'section'   =>  'dashboard'  
        );

        return view('home')->with($data);
    }

    public function dialerCall(Request $request)
    {
        
        $mobile = $request->phone_no ? Helper::properMobile($request->phone_no): Helper::properMobile($request->ANI);

        if ($mobile) {

            if (OBD::checkCall($mobile)) {
               return Redirect::to('/lead/addLead/?phone_no=OBD');
            };

            if (!$mobile) {
                return $this->index();
            }

            $lead = DB::table('marketing_details')
                        ->where('phone', $mobile)->first();
            
            if (!$lead) {
            
                $lead = DB::table('marketing_details')
                        ->where('mobile', $mobile)
                        ->first();
            }

            if ($lead) {
               return Redirect::to('/lead/' . $lead->id . '/viewDispositions');
            }
            return Redirect::to('/lead/addLead/?phone_no=' . $mobile);
        }

        return $this->index();
    }

    

    public function viewAddLeadForm(Request $request)
    {
        $mobile = $request->phone_no ? $request->phone_no : ""; 
        $data = array(
            'menu'      => 'lead',
            'section'   => 'partials.add',
            'mobile'    => $mobile
        );

        return view('home')->with($data);
    }

    public function getHotPipelines()
    {
        $leads = LeadsStatus::getPipelinesByStatus();
    }

    public function search(Request $request)
    {
        //Update lead_id in Patient table
        //DB::update("UPDATE patient_details AS p SET lead_id = (SELECT id FROM marketing_details m WHERE m.clinic=p.clinic AND m.enquiry_no=p.enquiry_no) WHERE lead_id = 0");
        //Update patient_id in Fees table
        //DB::update("UPDATE fees_details AS f SET patient_id = (SELECT id FROM patient_details AS p WHERE f.clinic=p.clinic AND f.registration_no=p.registration_no) WHERE patient_id = 0");
        

        /*if (Auth::user()->hasRole('cre') && Auth::id() <> 93) { //Give access to Neetu Chawla
            return "You are not authorized to view this Page. Kindly contact your Senior or Marketing Team";
        }*/
        
        $enquiry_no = trim($request->enquiry_no);
        $name = trim($request->name);
        $mobile = trim($request->mobile);
        $email = trim($request->email);
        $leads = array();
        $patients = array();
        $searchFor = NULL;


        
           
        if ($enquiry_no || $name || $mobile || $email) {
                
              
            $query = Lead::with('dnc');

            if($name) {

                $query = $query->where('name', 'LIKE', "%$name%");

                $searchFor .= " Name = " . $name . "<br>";
            }

            if ($mobile) {

                $query = $query->where('phone', 'LIKE', "$mobile%")
                        ->orWhere('mobile', 'LIKE', "$mobile%");
                $searchFor .= " Phone/Mobile = " . $mobile . "<br>";

            }

            if ($email) {

                $query = $query->where('email', '=', $email)
                        ->orWhere('email_alt', '=', $email);

                $searchFor .= " Email = " . $email . "<br>";

            }

            if ($enquiry_no) {

                $query = $query->leftJoin('patient_details as p', 'marketing_details.id' , '=', 'p.lead_id')
                                ->where('p.id', $enquiry_no)
                                ->orWhere('p.registration_no', $enquiry_no)
                                ->orwhere('marketing_details.enquiry_no', '=', $enquiry_no)
                                ->orWhere('marketing_details.id', $enquiry_no);

                $searchFor .= " Enquiry or Registration No = " . $enquiry_no . "<br>";
            }

            $leads = $query->select('marketing_details.*')->get();


            /*$query = DB::table('marketing_details AS m')
                        ->leftJoin('lead_dncs AS d', 'm.id', '=', 'd.lead_id')
                        ->leftJoin('patient_details AS p', 'm.id', '=', 'p.lead_id');
                
            if ($name) {

                $query = $query->where('name', 'LIKE', "%$name%");
                $searchFor .= " Name = " . $name . "<br>";

            }

            if ($enquiry_no) {

                $query = $query->where('m.enquiry_no', '=', $enquiry_no)
                                ->orWhere('m.id', $enquiry_no)
                                ->orWhere('p.registration_no', $enquiry_no)
                                ->orWhere('p.id', $enquiry_no);

                $searchFor .= " Enquiry or Registration No = " . $enquiry_no . "<br>";
            }

            if ($mobile) {

                $query = $query->where('m.phone', 'LIKE', "$mobile%")
                        ->orWhere('mobile', 'LIKE', "$mobile%");
                $searchFor .= " Phone/Mobile = " . $mobile . "<br>";

            }

            if ($email) {

                $query = $query->where('email', '=', $email)
                        ->orWhere('email_alt', '=', $email);

                $searchFor .= " Email = " . $email . "<br>";

            }

            $leads = $query->select("m.blacklist", "m.id", "m.name", "m.clinic", "m.enquiry_no", "m.email", "m.email_alt", "m.phone", "m.mobile", "p.id AS patient_id", "p.registration_no", "d.id as dnc")
                    ->get();*/
            //dd($leads);
        } 

        $data = array(
            'menu'      => 'lead',
            'section'   => 'search',
            'searchFor' =>  $searchFor,
            'leads'     => $leads
        );

        return view('home')->with($data);
    }

    public function showLead($id)
    {
        /*$lead = DB::table('marketing_details AS m')
                ->leftJoin('patient_details AS p', function($join)
                    {
                        $join->on('m.clinic', '=', 'p.clinic');
                        $join->on('m.enquiry_no', '=', 'p.enquiry_no');
                    })
                ->where('m.id', '=', $id)
                ->select('m.id', 'm.clinic', 'm.enquiry_no', 'm.name', 'm.phone', 'm.mobile', 'p.registration_no')
                ->first();*/
        $lead = Lead::find($id);

        $data = array(
            'menu'      => 'lead',
            'section'   => 'index',
            'lead'      =>  $lead
        );

        return view('home')->with($data);
    }

    public function viewDispositions($id)
    {
             
        $lead = Lead::with('patient','dispositions.master')
                ->with('dialer')
                ->with(['disposition' => function($q){
                    $q->where('name','=',Auth::user()->employee->name);
                    $q->whereBetween('created_at', Array(date('Y-m-d 0:0:0'), date('Y-m-d 23:59:59')));
                }])
                ->find($id);
        
        if ($lead->country!='IN'){
            $city = new City;
            $flag = false;

            if ($lead->country && $lead->country!='' && $city->where('country_code',$lead->country)->first()){
                $city = $city->where('country_code',$lead->country);
                $flag = true;
            }
            if ($lead->state && $lead->state!='' && strpos($lead->state,'.') && $city->where('region_code',trim(explode('.',$lead->state)[1]))->first()){
                $city = $city->where('region_code',trim(explode('.',$lead->state)[1]));
                $flag = true;
            }
            if ($lead->city && $lead->city!='' && $city->where('name',$lead->city)->first()){
                $city = $city->where('name',$lead->city);                       
                $flag = true;
            }
            $msg = 'This is an international Client. Please Check local time before calling';            
            if($city->first() && $city->first()->getLocalTime())
                $msg = $msg.'<br>Local Time : '.$city->first()->getLocalTime();
            if ($flag && $city->first() && $city->first()->country_code =='IN');            
            else
                Session::flash('message', $msg);
                Session::flash('status', 'error');
        }                           
           
        $dept =  1;
        if (Auth::user()->hasRole('cre') || Auth::user()->hasRole('sales')) {
           $dept =  1;
        }
        elseif (Auth::user()->hasRole('doctor') || Auth::user()->hasRole('nutritionist') || Auth::user()->hasRole('service')) {
           $dept =  2;
        }

        $dialer_dispositions = array();

        try {

            $dialer_dispositions = DB::connection('pgsql')->table('ct_recording_log as crl')
                            ->where('crl.phonenumber', '=', $lead->phone);
            
                if(trim($lead->mobile) <> '' && ( $lead->mobile <> $lead->phone)) {
                    $dialer_dispositions = $dialer_dispositions->orWhere('crl.phonenumber', '=', $lead->mobile);
                }
                        
                
            $dialer_dispositions = $dialer_dispositions->join(DB::raw("(SELECT distinct disponame, dispodesc FROM ct_dispositions) AS c"), function($join) {
                                    $join->on('crl.disposition', '=', 'c.disponame');
                                    })
                                ->join(DB::raw("(SELECT username, userfullname FROM ct_user) AS u"), function($join) {
                                     $join->on('crl.username', '=', 'u.username');
                                     })
                                ->select('crl.username', 'crl.eventdate', 'crl.disposition', 'crl.duration', 'crl.filename', 'c.dispodesc', 'u.userfullname')
                                ->orderby('crl.eventdate','desc')
                                ->limit(10)->get();
            
        } catch (\Exception $e) {
            
            Session::flash("message", "Error connecting with Dialer Database");
            Session::flash("status", "error");
        }       
                              

    
        $data = array(
            'menu'          =>  'lead',
            'section'       =>  'partials.dispositions',
            'dept'          =>  $dept,
            'dialer_dispositions' => $dialer_dispositions,
            'lead'          =>  $lead
        );

        return view('home')->with($data);
            
    }


    public function selfAssign(Request $request, $id)
    {
        if(Auth::user()->hasRole('cre')) 
        {
            $lead = Lead::find($id);
            if(LeadCre::ifMultipleCreOnSameDate($lead)) {
                return "Cannot add multiple CRE on same date";
            }
            if(LeadCre::ifSameCre($lead, $request->cre)) {
                return "Cannot add same CRE";
            }
            if(LeadCre::saveCre($lead))
                return "Lead Assigned to your Account!";
            else
                return "Somme Error Occured!";  
            return "Error";
        }
        return "You are not a CRE";
    }

    public function saveDisposition()
    {
        return "Success";
    }

    public function showPersonalDetails($id)
    {
        $lead = Lead::find($id);
        
        $data = array(
            'menu'          =>  'lead',
            'section'       =>  'partials.personal',
            'lead'          =>  $lead
        );

        return view('home')->with($data);   
    }

    public function savePersonalDetails(Request $request, $id)
    {
        if($request->height && is_numeric($request->height) && $request->height >=100 && $request->height <= 300);
        elseif($request->height)
            return 'Please enter a valid height in cms';

        try 
        {
            $lead = Lead::updateLead($id, $request);

            return "Personal details updated successfully!!!";
            
        } 
        catch (Exception $e) 
        {
            return "Error " . $e;
        }
            
    }

    public function showContactDetails($id)
    {
        $lead = Lead::find($id);

        $cod = Cod::checkAvailability($lead->zip);
        
        $data = array(
            'menu'          =>  'lead',
            'section'       =>  'partials.contact',
            'lead'          =>  $lead,
            'cod'           =>  $cod
        );

        return view('home')->with($data);  
    }


    public function saveContactDetails(Request $request, $id)
    {
        try 
        {
            if (Lead::isDuplicateMobile($id, Helper::properMobile($request->phone))) {
               return "Duplicate Phone no!!!";
            }

            if (trim($request->email) <> "" && Lead::isDuplicateEmail($id, $request->email)) {
               return "Duplicate Email address!!!";
            }

            $lead = Lead::updateLead($id, $request);

            return "Contact details updated successfully!!!";
            
        } 
        catch (Exception $e) 
        {
            return "Error " . $e;
        }            
    }

    public function showReferences($id)
    {
        $lead = Lead::find($id);

        $references = Lead::with('patient')
                        ->join('lead_sources AS s', function($join)
                        {
                            $join->on('marketing_details.clinic', '=', 's.clinic');
                            $join->on('marketing_details.enquiry_no', '=', 's.enquiry_no');
                        })
                        ->leftJoin('voices as v', 'v.id', '=', 's.voice_id')
                        ->where("referrer_clinic", $lead->clinic)
                        ->where("referrer_enquiry_no", $lead->enquiry_no)
                        ->select("marketing_details.*", "s.created_at AS date", "sourced_by", "v.name as voice")
                        ->orderBy('marketing_details.id', 'DESC')
                        ->get();
        
        $data = array(
            'menu'          =>  'lead',
            'section'       =>  'partials.references',
            'lead'          =>  $lead,
            'references'    =>  $references
        );

        return view('home')->with($data);  
    }

    public function saveReference(Request $request, $id)
    {
        if (trim($request->email) <> "" && Lead::isExistingEmail($request->email)) {
            return "Duplicate Email";
        }
        if (trim($request->mobile) <> "" && Lead::isExistingMobile($request->mobile)) {
            return "Duplicate Mobile/Phone";
        }

        $clinic = "C1";
        $enquiry_no = Lead::getNewEnquiryNo($clinic);

        $lead = Lead::saveLead($request);

        //Save Lead Status
        $leadStatus = LeadStatus::saveStatus($lead, 1);

        //Save Lead Source
        $leadSource = LeadSource::saveSource($lead, $request);

        if (Auth::user()->hasRole('cre')) 
        {
            $leadCre = LeadCre::saveCre($lead, Auth::user()->employee->name);
        }

        return "New Reference Lead created successfully";
    }

    public function viewDetails($id)
    {
        $lead = Lead::with('patient', 'patient.fee', 'patient.fees.source', 'patient.diets', 'patient.primaryNtr', 'patient.secondaryNtr', 'patient.doctors')
                ->with('status')
                ->find($id);
                
        if (!$lead) {
            echo "Lead does not exist";
            die();
        }

        $data = array(
            'menu'          =>  'lead',
            'section'       =>  'partials.details',
            'lead'          =>  $lead
        );

        return view('home')->with($data);  
    }

    public function saveLead(Request $request)
    {
        if (Lead::isExistingMobile($request->mobile)) {
            return "Duplicate Mobile";
        }
        if (trim($request->email) <> "" && Lead::isExistingEmail($request->email)) {
            return "Duplicate Email ";
        }

        $lead = Lead::addLead($request);
        $this->check($lead);
        //dd($lead);

        return redirect("/lead/" . $lead->id . "/viewDispositions");        
    }

    public function check($lead)
    {
        $dnd = new DND;

        if($dnd->scrub($lead->phone) == true){

            echo '<p>Phone : '.$lead->id.$lead->name;
            Lead::setPhoneDNDStatus($lead, 1);

        } elseif ($dnd->scrub($lead->phone) == false) {
            Lead::setPhoneDNDStatus($lead, 0);
        }


        
        
        if($dnd->scrub($lead->mobile) == true){

            Lead::setMobileDNDStatus($lead, 1);
            echo '<p>Mobile : '.$lead->id.$lead->name;

        } elseif ($dnd->scrub($lead->mobile) == false) {

            Lead::setMobileDNDStatus($lead, 0);
        }
    }


    public function deleteSource(Request $request)
    {
        try {
            if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing'))
            {
                $source = LeadSource::find($request->id);
                $source->delete();
                return "Source Deleted";
            }
            
            return "Not authorized";
            
        } catch (\Illuminate\Database\QueryException $e) {
            return $e;
        }
        
    }

    public function deleteCre(Request $request)
    {
        try {
            if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('marketing'))
            {
                $cre = LeadCre::find($request->id);
                $lead = Lead::find($cre->lead_id);

                $cre->delete();

                $this->updateCre($lead);

                return "CRE Deleted";
            }
            
            return "Not Authorised";
            
        } catch (\Illuminate\Database\QueryException $e) {
            return $e;
        }
        
    }

    public function updateCre($lead)
    {
        $cre = LeadCre::where('lead_id', $lead->id)->orderBy('id', 'desc')->limit(1)->first();

        $lead->cre_name = $cre->cre;
        $lead->save();
    }

    public function saveCre(Request $request)
    {
        $lead = Lead::find($request->id);

        if(LeadCre::ifMultipleCreOnSameDate($lead)) {
            return "Cannot add multiple CRE on same date";
        }

        if(LeadCre::ifSameCre($lead, $request->cre)) {
            return "Cannot add same CRE";
        }

        if(LeadCre::saveCre($lead, $request->cre)) {
            return "CRE Saved";
        }

        return "Error";

    }


    public function saveSource(Request $request)
    {
        $lead = Lead::find($request->id);

        if (LeadSource::isExistingSource($lead->id, $request->source)) {
            return "Error : Source already exists";
        }
        
        if (LeadSource::saveSource($lead, $request)) {
           return "Source saved successfully";
        }

        return "Error";
    }

    public function uploadLead(Request $request)
    {
        $filename = $_FILES["file"]["tmp_name"];
        $header = true;

        if($_FILES["file"]["size"] > 0)
        {
            $fields = array();

            $file = fopen($filename, "r");

            echo "<thead>". PHP_EOL;

            while (($data = fgetcsv($file, 10000, ",")) !== FALSE)
            {                
                $status = "<i class='fa fa-check-square green' title='Lead Added'></i>";
                $name = isset($data[0]) ? $data[0] : '';
                $phone = isset($data[1]) ? $data[1] : '';
                $email = isset($data[2]) ? $data[2] : '';
                $country = isset($data[3]) ? $data[3] : '';
                $state = isset($data[4]) ? $data[4] : '';
                $city = isset($data[5]) ? $data[5] : '';
                $src = isset($data[6]) ? $data[6] : '';
                $query = isset($data[7]) ? $data[7] : '';
                $cre = isset($data[8]) ? $data[8] : '';


                echo "<tr>". PHP_EOL;

                if ($header) {
                    
                    echo "<th>" . htmlspecialchars($name) . "</th>" . PHP_EOL;
                    echo "<th>" . htmlspecialchars($phone) . "</th>" . PHP_EOL;
                    echo "<th>" . htmlspecialchars($email) . "</th>" . PHP_EOL;
                    echo "<th>" . htmlspecialchars($country) . "</th>" . PHP_EOL;
                    echo "<th>" . htmlspecialchars($state) . "</th>" . PHP_EOL;
                    echo "<th>" . htmlspecialchars($city) . "</th>" . PHP_EOL;
                    echo "<th>" . htmlspecialchars($src) . "</th>" . PHP_EOL;
                    echo "<th>" . htmlspecialchars($query) . "</th>" . PHP_EOL;
                    echo "<th>" . htmlspecialchars($cre) . "</th>" . PHP_EOL;
                    echo "<th>Status</th>" . PHP_EOL;
                    echo "</thead>";
                    echo "<tbody>";


                    if (trim(strtolower($name)) <> 'name' || trim(strtolower($phone)) <> 'phone' || trim(strtolower($email)) <> 'email' || trim(strtolower($country)) <> 'country' || trim(strtolower($state)) <> 'state' || trim(strtolower($city)) <> 'city' || trim(strtolower($src)) <> 'source' || trim(strtolower($query)) <> 'query' || trim(strtolower($cre)) <> 'cre') {
                        echo "<th> Incorrect file Header format </th>" . PHP_EOL;
                        die();
                    }

                    $header = FALSE;
                    continue;
                }

                //If Phone, Source, or Cre blank
                if(trim($phone) == "" || trim($src) == "" || trim($cre) == "")
                {
                    $status = "<i class='fa fa-times-circle red' title='Incomplete Details'></i>";
                    echo "<td><em class='red'>" . $name . "</em></td>";
                    echo "<td><em class='red'>" . $phone. "</em></td>";
                    echo "<td><em class='red'>" . $email . "</em></td>";
                    echo "<td><em class='red'>" . $country . "</em></td>";
                    echo "<td><em class='red'>" . $state . "</em></td>";
                    echo "<td><em class='red'>" . $city . "</em></td>";
                    echo "<td><em class='red'>" . $src . "</em></td>";
                    echo "<td><em class='red'>" . $query . "</em></td>";
                    echo "<td><em class='red'>" . $cre . "</em></td>";
                    echo "<td><em class='red'>" . $status . "</em></td>";
                    echo "</tr>" . PHP_EOL;
                    continue;
                }   


                $source = Source::where('source', $src)->first(); 
                if (!$source) {
                    echo "Incorrect Source";
                    continue;
                }           

                $q = Lead::where('phone', $phone)
                            ->orWhere('mobile', $phone);

                if ($email) {
                    $q = $q->orWhere('email', $email);
                }
                            
                $lead = $q->first();
                
                if($lead)
                {
                    $status = "<i class='fa fa-times red' title='Duplicate Phone or Email'></i>";                  
                }
                else
                {    

                    $lead = new Lead;

                    $lead->clinic = "C1";
                    $lead->enquiry_no = Lead::getNewEnquiryNo("C1");
                    $lead->entry_date = date('Y-m-d h:i:s');
                    $lead->name = $name;
                    $lead->phone = Helper::properMobile($phone);
                    $lead->mobile = Helper::properMobile($phone);
                    $lead->email = $email;
                    $lead->country = $country;
                    $lead->state = $state;
                    $lead->city = $city;
                    $lead->source_id = $source->id;
                    $lead->status_id = 1;
                    $lead->created_by = Auth::user()->employee->name;

                    $lead->save();

                    $request = new Request;
                    $request->source = $source->id;
                    $request->remark = $query;

                    //Save LeadSource
                    if (LeadSource::ifSameSource($lead, $source->id)) 
                    {
                        $status = "<i class='fa fa-check-square red' title='Lead Added but Duplicate Source'></i>";
                    } 
                    else 
                    {
                        LeadSource::saveSource($lead, $request);
                    }

                    //Save LeadCre
                    if (LeadCre::ifSameCre($lead, $cre)) {
                        $status = "<i class='fa fa-check-square yellow' title='Lead Added but Duplicate CRE'></i>";
                    } else {
                        LeadCre::saveCre($lead, $cre);
                    }

                    //Save LeadStatus
                    if (LeadStatus::ifSameStatus($lead, $status)) {
                        $status = "<i class='fa fa-check-square green' title='Lead Added but Duplicate Status'></i>";
                    } else {
                        LeadStatus::saveStatus($lead, 1);
                    }
                
                }


                echo "<td><a href='/lead/" . $lead->id . "/viewDetails' target='_blank'>" . $lead->name . "</a></td>";
                echo "<td>" . $lead->phone. "</td>";
                echo "<td>" . $lead->email . "</td>";
                echo "<td>" . $lead->country . "</td>";
                echo "<td>" . $lead->state . "</td>";
                echo "<td>" . $lead->city . "</td>";
                echo "<td>";
                echo $lead->source_id > 0  ? $lead->source->source_name : " ";
                echo "</td>";
                echo "<td>" . $query . "</td>";
                echo "<td>" . $cre . "</td>";
                echo "<td>" . $status . "</td>";
                echo "</tr>" . PHP_EOL;
            }

            echo "</tbody>" . PHP_EOL;

        }
    }

    public function delete(Request $request, $id)
    {
        $lead = Lead::find($id);
        
        if ($request->_token) {

            $lead2 = Lead::find($request->lead);


            if (isset($lead->patient)) {
                //Move fees details
                $fees = Fee::where('patient_id', $lead->patient->id)->get();
                foreach ($fees as $fee) {
                    $fee->patient_id = $lead2->patient->id;
                    $fee->clinic = $lead2->clinic;
                    $fee->registration_no = $lead2->patient->registration_no;
                    $fee->save();
                }

                //Move Diet details
                $diets = DB::table('diet_assign')
                        ->where('patient_id', $lead->patient->id)
                        ->update(['patient_id' => $lead2->patient->id, 'clinic' => $lead2->clinic, 'registration_no' => $lead2->patient->registration_no]);
                

                //Move fitness details
                $fitnesses = DB::table('fitness_details')
                            ->where('clinic', $lead->clinic)
                            ->where('registration_no', $lead->patient->registration_no)
                            ->update(['clinic' => $lead2->clinic, 'registration_no' => $lead2->patient->registration_no]);
                
                //Move MEDICALS
                $medicals = DB::table('medical')
                            ->where('clinic', $lead->clinic)
                            ->where('registration_no', $lead->patient->registration_no)
                            ->update(['clinic' => $lead2->clinic, 'registration_no' => $lead2->patient->registration_no]);
                

                //DELETE SUIT NOT SUIT
                DB::table('suit_ntsuit')
                            ->where('clinic', $lead->clinic)
                            ->where('registration_no', $lead->patient->registration_no)
                            ->delete();

                //DELETE CONSTITUTION
                DB::table('constitution')
                            ->where('clinic', $lead->clinic)
                            ->where('registration_no', $lead->patient->registration_no)
                            ->delete();
            }

            
            if($request->lead)
            {
                //Move or Delete Lead Source
                $sources = LeadSource::where('lead_id', $id)->get();
                foreach ($sources as $source) {
                    $source->lead_id = $lead2->id;
                    $source->clinic = $lead2->clinic;
                    $source->enquiry_no = $lead2->enquiry_no;
                    $source->save();
                }

                //MOVE REFERENCES
                $references = LeadSource::where('referrer_id', $id)->get();
                foreach ($references as $reference) 
                {
                    $reference->referrer_id = $lead2->id;
                    $reference->referrer_clinic = $lead2->clinic;
                    $reference->referrer_enquiry_no = $lead2->enquiry_no;
                    $reference->save();
                }    

                //Move or Delete Lead Cre
                $cres = LeadCre::where('lead_id', $id)->get();
                foreach ($cres AS $cre)
                {
                    $cre->lead_id = $lead2->id;
                    $cre->clinic = $lead2->clinic;
                    $cre->enquiry_no = $lead2->enquiry_no;
                    $cre->save();
                }

                //Move or Delete Call Dispositions
                $dispositions = CallDisposition::where('lead_id', $id)->get();            
                foreach ($dispositions as $disposition) 
                {
                    
                    $disposition->lead_id = $lead2->id;
                    $disposition->clinic = $lead2->clinic;
                    $disposition->enquiry_no = $lead2->enquiry_no;
                    $disposition->save();
                }

                $deleteStatus = LeadStatus::where('lead_id', $id)->delete();
            }
            else
            {
                $dispositions = CallDisposition::where('lead_id', $id)->delete(); 
                $sources = LeadSource::where('lead_id', $id)->delete();
                $cres = LeadCre::where('lead_id', $id)->delete();
                $deleteStatus = LeadStatus::where('lead_id', $id)->delete();
            }

            //Delete Lead
            Lead::destroy($id);

                
        }

        $data = array(
            'menu'      => 'lead',
            'section'   => 'delete',
            'lead'      => $lead
            );

        return view('home')->with($data);
    }

    public function saveVoice(Request $request)
    {
        $leadSource = LeadSource::find($request->id);

        $leadSource->voice_id = $request->value;
        $leadSource->save();

        $voice = Voice::find($request->value);
        
        return $voice->name;
    }

    
}
