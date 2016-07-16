<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Leads;
use App\Models\Lead;
use App\Models\Patient;
use App\Models\Fees;
use App\Models\LeadSource;
use App\Models\LeadCre;
use App\Models\LeadStatus;
use App\Models\User;
use App\Models\Notification;
use DB;
use Auth;
use App\Support\Helper;
use Redirect;
use Carbon;

class LeadsController extends Controller
{
    public function update()
    {
        $leads = Lead::with('cre')
                /*->whereHas('cre', function($q) {
                    $q->whereNotNull('created_at');
                })*/
                ->has('cre', '>=', 1)
                //->where('cre_id', '>', 500)
                ->whereNull('cre_assigned_at')
                ->limit(20000)
                ->orderBy('id', 'desc')
                ->get();

        foreach ($leads as $lead) {
            $lead->cre_id = $lead->cre ? $lead->cre->user_id : null;
            $lead->cre_assigned_at = $lead->cre?$lead->cre->created_at:null;
            $lead->save();
            echo $lead->id . " - " . $lead->name . " - " . $lead->cre_assigned_at . "<p>";
        }
    }

    public function dialerCall(Request $request)
    {
        
        $mobile = $request->phone_no ? $request->phone_no: $request->ANI;

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

    public function viewAddLeadForm(Request $request)
    {
        $mobile = $request->phone_no ? $request->phone_no : ""; 
        $data = array(
            'menu'      => 'lead',
            'section'   => 'add',
            'mobile'    => $mobile
        );

        return view('home')->with($data);
    }

    public function showLeadsByCRE()
    {
        $leads = Leads::filterByCRE($start_date, $end_date, $cre);

        return json_encode($leads);

    }

    public function getHotPipelines()
    {
        $leads = LeadsStatus::getPipelinesByStatus();
    }

    public function searchLeads(Request $request)
    {
        $enquiry_no = trim($request->enquiry_no);
        $name = trim($request->name);
        $mobile = trim($request->mobile);
        $email = trim($request->email);
        $leads = array();
        $patients = array();
        $searchFor = NULL;


        
           
        if ($enquiry_no || $name || $mobile || $email) {
                
              
            $query = Leads::with('patient');
                
            if ($name) {

                $query = $query->where('name', 'LIKE', "%$name%");
                $searchFor .= " Name = " . $name . "<br>";

            }

            if ($enquiry_no) {

                $query = $query->where('enquiry_no', '=', $enquiry_no)
                                ->orWhere('id', $enquiry_no);

                $searchFor .= " Enquiry or Registration No = " . $enquiry_no . "<br>";
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

            $leads = $query->get();
            //dd($leads);

            if ($enquiry_no) {
                $patients = Patient::with('lead')
                    ->where('id', $enquiry_no)
                    ->orWhere('registration_no', $enquiry_no)
                    ->get();
            }
            

            /*$leads = DB::table('marketing_details AS m')
                    ->leftJoin('patient_details AS p', function($join)
                        {
                            $join->on('m.clinic', '=', 'p.clinic');
                            $join->on('m.enquiry_no', '=', 'p.enquiry_no');
                        })
                    ->where('name', 'LIKE', "%$name%")
                    ->get(array('m.id', 'm.enquiry_no', 'm.clinic', 'name', 'registration_no', 'm.phone', 'm.mobile', 'm.email'));
        */
        //}

        /*if ($enquiry_no) {
            $searchFor = "Enquiry or Registration No = " . $enquiry_no;
            $leads = DB::table('marketing_details AS m')
                    ->leftJoin('patient_details AS p', function($join)
                        {
                            $join->on('m.clinic', '=', 'p.clinic');
                            $join->on('m.enquiry_no', '=', 'p.enquiry_no');
                        })
                    ->where('m.enquiry_no', '=', $enquiry_no)
                    ->orWhere('p.registration_no', '=', $enquiry_no)
                    ->get(array('m.id', 'm.enquiry_no', 'm.clinic', 'name', 'registration_no', 'm.phone', 'm.mobile', 'm.email'));
        }

        if ($mobile) {
            $searchFor = "Mobile or Phone = " . $mobile;
            $leads = DB::table('marketing_details AS m')
                    ->leftJoin('patient_details AS p', function($join)
                        {
                            $join->on('m.clinic', '=', 'p.clinic');
                            $join->on('m.enquiry_no', '=', 'p.enquiry_no');
                        })
                    ->where('m.phone', 'LIKE', "$mobile%")
                    ->orWhere('mobile', 'LIKE', "$mobile%")
                    ->get(array('m.id', 'm.enquiry_no', 'm.clinic', 'name', 'registration_no', 'm.phone', 'm.mobile', 'm.email'));
        }

        if ($email) {
            $searchFor = "Email = " . $email;
            $leads = DB::table('marketing_details AS m')
                    ->leftJoin('patient_details AS p', function($join)
                        {
                            $join->on('m.clinic', '=', 'p.clinic');
                            $join->on('m.enquiry_no', '=', 'p.enquiry_no');
                        })
                    ->where('email', '=', $email)
                    ->orWhere('email_alt', '=', $email)
                    ->get(array('m.id', 'm.enquiry_no', 'm.clinic', 'name', 'registration_no', 'm.phone', 'm.mobile', 'm.email'));
        }*/
        } 

        $data = array(
            'menu'      => 'lead',
            'section'   => 'search',
            'searchFor' =>  $searchFor,
            'leads'     => $leads,
            'patients'  => $patients
        );

        return view('home')->with($data);
    }

    public function showLead($id)
    {
        $lead = DB::table('marketing_details AS m')
                ->leftJoin('patient_details AS p', function($join)
                    {
                        $join->on('m.clinic', '=', 'p.clinic');
                        $join->on('m.enquiry_no', '=', 'p.enquiry_no');
                    })
                ->where('m.id', '=', $id)
                ->select('m.id', 'm.clinic', 'm.enquiry_no', 'm.name', 'm.phone', 'm.mobile', 'p.registration_no')
                ->first();

        $data = array(
            'menu'      => 'lead',
            'section'   => 'index',
            'lead'      =>  $lead
        );

        return view('home')->with($data);
    }

    public function viewDispositions($id)
    {
        $lead = new Lead;
        $lead->init($id);

        $dept =  1;
        if (Auth::user()->hasRole('cre')) {
           $dept =  1;
        }
        elseif (Auth::user()->hasRole('nutritionist') || Auth::user()->hasRole('service')) {
           $dept =  2;
        }
                
        $dispositions = DB::table('call_dispositions AS cd')
                    ->leftJoin('m_call_disposition AS mcd', 'mcd.id', '=', 'cd.disposition')
                    ->where('cd.clinic', '=', $lead->clinic)
                    ->where('cd.enquiry_no', '=', $lead->enquiry_no)
                    ->orderBy('created_at', 'DESC')
                    ->get();

        $data = array(
            'menu'          =>  'lead',
            'section'       =>  'dispositions',
            'dept'          =>  $dept,
            'lead'          =>  $lead,
            'dispositions'  =>  $dispositions
        );

        return view('home')->with($data);
    }

    public function saveDisposition()
    {
        return "Success";
    }

    public function showPersonalDetails($id)
    {
        $lead = new Lead;
        $lead->init($id);
        
        $data = array(
            'menu'          =>  'lead',
            'section'       =>  'personal',
            'lead'          =>  $lead
        );

        return view('home')->with($data);   
    }

    public function savePersonalDetails(Request $request, $id)
    {
        try 
        {
            $lead = Lead::where('id',$id)
                ->update([
                    'name'      => Helper::proper_case($request->name),
                    'dob'       => Helper::emptyStringToNull($request->dob),
                    'gender'    => Helper::emptyStringToNull($request->gender),
                    'height'    => Helper::emptyStringToNull($request->height),
                    'weight'    => Helper::emptyStringToNull($request->weight)
                ]);

            return "Personal details updated successfully!!!";
            
        } 
        catch (Exception $e) 
        {
            return "Error " . $e;
        }
            
    }

    public function showContactDetails($id)
    {
        $lead = new Lead;
        $lead->init($id);
        
        $data = array(
            'menu'          =>  'lead',
            'section'       =>  'contact',
            'lead'          =>  $lead
        );

        return view('home')->with($data);  
    }


    public function saveContactDetails(Request $request, $id)
    {
        try 
        {
            if (Lead::isDuplicateMobile($id, $request->phone)) {
               return "Duplicate Phone no!!!";
            }

            if (Lead::isDuplicateEmail($id, $request->email)) {
               return "Duplicate Email address!!!";
            }

            $lead = Lead::where('id', $id)
                ->update([
                    'mobile'        => $request->mobile,
                    'phone'         => $request->phone,
                    'email'         => $request->email,
                    'email_alt'     => $request->email_alt,
                    'skype'         => $request->skype,
                    'address'       => $request->address,
                    'country'       => $request->country,
                    'state'         => $request->state,
                    'city'          => $request->city,
                    'zip'           => $request->zip         
                ]);

            return "Contact details updated successfully!!!";
            
        } 
        catch (Exception $e) 
        {
            return "Error " . $e;
        }            
    }

    public function showReferences($id)
    {
        $lead = new Lead;
        $lead->init($id);

        $references = DB::table("marketing_details AS m")
                        ->join('lead_sources AS s', function($join)
                        {
                            $join->on('m.clinic', '=', 's.clinic');
                            $join->on('m.enquiry_no', '=', 's.enquiry_no');
                        })
                        ->where("referrer_clinic", $lead->clinic)
                        ->where("referrer_enquiry_no", $lead->enquiry_no)
                        ->select("m.*", "s.created_at AS date", "sourced_by")
                        ->orderBy('m.id', 'DESC')
                        ->get();
        
        $data = array(
            'menu'          =>  'lead',
            'section'       =>  'references',
            'lead'          =>  $lead,
            'references'    =>  $references
        );

        return view('home')->with($data);  
    }

    public function saveReference(Request $request, $id)
    {
        if (trim($request->email) <> "" && Lead::isDuplicateEmail($id, $request->email)) {
            return "Duplicate Email";
        }
        if (Lead::isDuplicateMobile($id, $request->mobile)) {
            return "Duplicate Phone";
        }

        $clinic = "C1";
        $enquiry_no = Lead::getNewEnquiryNo($clinic);

        $lead = Lead::create(array(
                'clinic'    => $clinic,
                'enquiry_no' => $enquiry_no,
                'entry_date' => date('Y/m/d H:i:s'),
                'name'      => $request->name,
                'phone'     => $request->mobile,
                'mobile'    => $request->mobile,
                'email'     => $request->email,
                'country'   => $request->country,
                'state'     => $request->state,
                'city'      => $request->city
            ));
        $lead->save();

        $leadSource = new LeadSource;
        $lead = new Lead;
        $lead->init($id);

        $leadSource->clinic = $clinic;
        $leadSource->enquiry_no = $enquiry_no;
        $leadSource->source = 10;
        $leadSource->referrer_clinic = $lead->clinic;
        $leadSource->referrer_enquiry_no = $lead->enquiry_no;
        $leadSource->sourced_by = Auth::user()->employee->name;
        $leadSource->remarks    = $request->remarks;
        $leadSource->save();

        if (Auth::user()->hasRole('cre')) 
        {
            $leadCre = new LeadCre;

            $leadCre->clinic = $clinic;
            $leadCre->enquiry_no = $enquiry_no;
            $leadCre->cre = Auth::user()->employee->name;
            $leadCre->start_date = date('Y/m/d');
            $leadCre->save();
        }

        return "New Reference Lead created successfully";
    }

    public function viewDetails($id)
    {
        $lead = Lead::find($id);

        $fees = Fees::where('clinic', $lead->clinic)
                    ->where('registration_no', $lead->registration_no)
                    ->orderBy('entry_date', 'DESC')
                    ->get();

        $lastFee = Fees::where('clinic', $lead->clinic)
                    ->where('registration_no', $lead->registration_no)
                    ->orderBy('end_date', 'DESC')
                    ->first();
                    //dd($lastFee);
        if ($lastFee) {
            $now = date('Y-m-d') > $lastFee->end_date ? $lastFee->end_date : date('Y-m-d');
            $days = floor((strtotime(date($now)) - strtotime($lastFee->start_date))/(60*60*24));
            $daysLeft = 0;
            $lastFee->totalDays = floor((strtotime($lastFee->end_date) - strtotime($lastFee->start_date))/(60*60*24));
            if ($lastFee->totalDays <> 0) {
                $lastFee->progressPercentage = floor((($days)/$lastFee->totalDays)*100);
            }
            $lastFee->days = floor((strtotime(date($now)) - strtotime($lastFee->start_date))/(60*60*24));
        }
            
        $programStatus = Fees::programStatus($id);


        $cres = LeadCRE::where('clinic', $lead->clinic)
                    ->where('enquiry_no', $lead->enquiry_no)
                    ->orderBy('start_date', 'DESC')
                    ->get();

        $sources = DB::table('lead_sources as s')
                    ->join('m_lead_source as mls', 'mls.id', '=', 's.source')
                    ->leftJoin('marketing_details AS m', function($join)
                    {
                        $join->on('m.clinic', '=', 's.referrer_clinic');
                        $join->on('m.enquiry_no', '=', 's.referrer_enquiry_no');
                    })
                    ->where('s.clinic', $lead->clinic)
                    ->where('s.enquiry_no', $lead->enquiry_no)
                    ->orderBy('s.created_at', 'DESC')
                    ->select('m.id', 'mls.source_name', 'm.name', 's.remarks', 's.created_at')
                    ->get();

        $data = array(
            'menu'          =>  'lead',
            'section'       =>  'details',
            'lead'          =>  $lead,
            'cres'          =>  $cres,
            'sources'       =>  $sources,
            'fees'          =>  $fees,
            'lastFee'       =>  $lastFee,
            'programStatus' =>  $programStatus
        );

        return view('home')->with($data);  
    }

    public function saveLead(Request $request)
    {
        if (Lead::isExistingMobile($request->mobile)) {
            return "Duplicate Mobile";
        }
        if (isset($request->email) && Lead::isExistingEmail($request->email)) {
            return "Duplicate Email";
        }

        try 
        {
            $clinic = "C1";
            //Save New Lead
            $lead = new Leads;
            $lead->clinic = $clinic;
            $lead->enquiry_no = Lead::getNewEnquiryNo($clinic);
            $lead->name = Helper::proper_case($request->name);
            $lead->gender= $request->gender;
            $lead->mobile = $request->mobile;
            $lead->email = $request->email;
            $lead->country = $request->country;
            $lead->state = $request->state;
            $lead->city = $request->city;
            $lead->zip = $request->pin;
            $lead->save();

            //Save Lead Source
            $source = new LeadSource;
            $source->lead_id = $lead->id;
            $source->clinic = $lead->clinic;
            $source->enquiry_no = $lead->enquiry_no;
            $source->source = $request->source;
            $source->remarks = $request->remark;
            $source->save();

            //Save Lead Status
            LeadStatus::saveStatus($lead->id, $lead->clinic, $lead->enquiry_no, 1);

            //Save CRE
            if (Auth::user()->hasRole('cre')) {
               LeadCre::saveCre($lead->id, Auth::user()->id, $lead->clinic, $lead->enquiry_no, Auth::user()->employee->name);
            }

            return redirect("/lead/" . $lead->id . "/viewDispositions");
            
        } catch (\Illuminate\Database\QueryException $e) {
            return $e;
        }
           

    }

    public function get(Request $request)
    {
        $start_date = isset($request->start_date) ? Carbon::parse($request->start_date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');

        $end_date = isset($request->end_date) ? Carbon::parse($request->end_date)->format('Y-m-d 23:59:59') : Carbon::now(); 

        $query = Lead::with('status')
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->where('country', 'IN')
                    ->whereIn('status_id', $request->status_id)
                    ->has('dnc', '<', 1)
                    ->has('patient', '<', 1);

        return $query->get();
    }

    public function getLeadsByAssignedDate(Request $request)
    {
        $start_date = isset($request->start_date) ? Carbon::parse($request->start_date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');

        $end_date = isset($request->end_date) ? Carbon::parse($request->end_date)->format('Y-m-d 23:59:59') : Carbon::now(); 

        $limit = $request->limit ? : 100;

        $query = Lead::with('status', 'disposition.master', 'cre', 'patient', 'lsource')
                    ->whereHas('dispositions', function($q) {
                        $q->where('callback', '<=', Carbon::now());
                    })
                    ->whereBetween('cre_assigned_at', [$start_date, $end_date])
                    ->whereIn('status_id', $request->status_id)
                    ->has('dnc', '<', 1)
                    ->limit($limit);

        return $query->get();
    }

    public function viewChurn()
    {
        return view('leads.churn');
    }

    public function churn(Request $request)
    {
        $this->validate($request, [
            'ids'                   => 'required',
            'cre_id'                => 'required'
        ]);

        $user = User::find($request->cre_id);

        $leads = Lead::whereIn('id', $request->ids)->get();

        foreach ($leads as $lead) {

            if ($lead->cre_id == $user->id) {
                continue;
            }

            $lead->cres()->create([  
                'user_id'       => $request->cre_id,
                'cre'           => $user->employee->name,
                'start_date'    => Carbon::now(),
                'created_by'    => Auth::user()->employee->name
            ]);

            Notification::store(1, $lead->id, $user->id);
            Lead::updateCre($lead->id, $user->employee->name, $user->id);
        }
        //return $request->all();
    }
}
