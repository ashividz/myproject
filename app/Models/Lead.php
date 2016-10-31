<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LeadStatus;
use App\Models\Employee;
use DB;
use App\Support\Helper;
use Auth;
use Carbon\Carbon;
use App\DND;
use App\Models\Dialer\DialerCallDisposition;
use App\Models\Dialer\DialerCallBack;

use OwenIt\Auditing\AuditingTrait;

use App\Models\City;
class Lead extends Model
{
    use AuditingTrait;

    protected $table = 'marketing_details';
    protected $fillable = ['clinic', 'enquiry_no', 'entry_date', 'name', 'dob', 'gender', 'email', 'email_alt', 'phone', 'mobile', 'weight'];

    public function getDates()
    {
       return ['dob', 'created_at', 'updated_at'];
    }

    public function getMobileAttribute($value)
    {
        //do not check if it's a cron job
        if (Auth::check() && !Auth::user()->canViewContactDetails()) {
            return Helper::mask($value);
        }
        return $value;
    }

    public function getPhoneAttribute($value)
    {
        //do not check if it's a cron job
        if (Auth::check() && !Auth::user()->canViewContactDetails()) {
            return Helper::mask($value);
        }
        return $value;
    }

    public function getEmailAttribute($value)
    {
        //do not check if it's a cron job
        if (Auth::check() && !Auth::user()->canViewContactDetails()) {
            return Helper::mask(trim(strtolower(ucfirst($value))));
        }
        return trim(strtolower(ucfirst($value)));
    }

    public function getEmailAltAttribute($value)
    {
        //do not check if it's a cron job
        if (Auth::check() && !Auth::user()->canViewContactDetails()) {
            return Helper::mask(trim(strtolower(ucfirst($value))));
        }
        return trim(strtolower(ucfirst($value)));
    }


    public function query1()
    {
        return $this->belongsTo(Query::class, 'query_id');
    }

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function cre()
    {
        return $this->hasOne(LeadCre::class, 'lead_id')->latest();        
    }

    public function cres()
    {
        return $this->hasMany(LeadCre::class, 'lead_id')->orderBy('id', 'desc');        
    }    

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'cre_name', 'name');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function statuses()
    {
        return $this->hasMany(LeadStatus::class, 'lead_id')->orderBy('id', 'desc');
    }

    public function lsource()
    {
        return $this->belongsTo(Source::class, 'source_id');
    }

    public function source()
    {
        return $this->hasOne(LeadSource::class)->latest();
    }

    public function sources()
    {
        return $this->hasMany(LeadSource::class, 'lead_id')->orderBy('id', 'desc');
    }

    public function disposition()
    {
        return $this->hasOne(CallDisposition::class, 'lead_id')->latest();
    }

    public function dispositions()
    {
        return $this->hasMany(CallDisposition::class, 'lead_id')->orderBy('id', 'desc');
    }

    public function m_country()
    {
        return $this->hasOne(Country::class, 'country_code', 'country');
    }

    public function region()
    {
        return $this->hasOne(Region::class, 'region_code', 'state');
    }

    public function yuwow()
    {
        return $this->hasOne('App\Models\YuWoW\User', 'user_email', 'email');
    }

    public function dnc()
    {
        return $this->hasOne(LeadDnc::class);
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class)->withTimestamps();
    }

    private function bmi($weight, $height)
    {
        if ($weight && $height) {
            return round($weight*10000/($height*$height), 2);
        }
    }

    public function carts() 
    {
        return $this->hasMany(Cart::class)->orderBy('id', 'desc');
    }

    public function orders()
    {
        return $this->hasManyThrough(Order::class,Cart::class);
    }

    public function dialer()
    {
        return $this->hasOne(DialerPush::class, 'lead_id')->latest();
    }

    public function references()
    {
        return $this->belongsToMany(Lead::class, 'lead_sources', 'referrer_id', 'lead_id')
            ->withPivot('voice_id','created_by','created_at','sourced_by')
                    ->orderBy('id', 'desc');
    }
    
    public function addresses()
    {
        return $this->hasMany(LeadAddress::class);
    }

    public function dialerphonedispositions()
    {
        return $this->hasMany(DialerCallDisposition::class,'phonenumber','phone');
    }

    public function dialermobiledispositions()
    {
        return $this->hasMany(DialerCallDisposition::class,'phonenumber','mobile');
    }    
    
    public function phonecallbacks()
    {
        return $this->hasMany(DialerCallBack::class,'phonenumber','phone');
    }

    public function mobilecallbacks()
    {
        return $this->hasMany(DialerCallBack::class,'phonenumber','phone');
    }

    public function dialerphonedisposition()
    {
        return $this->hasOne(DialerCallDisposition::class,'phonenumber','phone');
    }

    public function dialermobiledisposition()
    {
        return $this->hasOne(DialerCallDisposition::class,'phonenumber','mobile');
    }    
    
    public function phonecallback()
    {
        return $this->hasOne(DialerCallBack::class,'phonenumber','phone');
    }

    public function mobilecallback()
    {
        return $this->hasOne(DialerCallBack::class,'phonenumber','phone');
    }

    public static function isDuplicateMobile($id, $mobile)
    {
       $lead = Lead::where('phone', '=', Helper::properMobile($mobile))
                ->where('id', '<>', $id)
                ->first();
        
        if ($lead) {
            return true;
        }
        return false;
    }

    public static function isDuplicateEmail($id, $email)
    {
        $lead = Lead::where('email', '=', $email)
                ->where('id', '<>', $id)
                ->first();

        if ($lead) {
            return true;
        }
        return false;
    }

    public static function isExistingMobile($mobile)
    {
        $lead = Lead::where('phone', Helper::properMobile($mobile))
                    ->orWhere('mobile', Helper::properMobile($mobile))
                    ->first();
        if ($lead) {
           return true;
        }
        return false;
    }

    public static function isExistingEmail($email)
    {
        $lead = Lead::where('email', $email)
                    ->first();
        if ($lead) {
           return true;
        }
        return false;
    }

    public static function getNewEnquiryNo($clinic)
    {
        $lead = Lead::where('clinic', $clinic)
                    ->orderBy('created_at', 'DESC')
                    ->first();

        return (int) $lead->enquiry_no + 1;
    }

    public static function saveLead($request, $i = NULL)
    {
        $clinic = "C1";
        $enquiry_no = Lead::getNewEnquiryNo($clinic);

        $lead = new Lead();
        $lead->clinic = $clinic;
        $lead->enquiry_no = $enquiry_no;

        if ($i) {
            $lead->query_id = $request->query_id[$i] ? $request->query_id[$i] : null;
            $lead->name = Helper::properCase($request->name[$i]);
            $lead->phone = isset($request->phone[$i]) ? Helper::properMobile($request->phone[$i]) : Helper::properMobile($request->mobile[$i]);
            $lead->mobile = isset($request->mobile[$i]) ? Helper::properMobile($request->mobile[$i]) : Helper::properMobile($request->phone[$i]);
            $lead->email = trim($request->email[$i]);

            $lead->country = $request->country[$i];
            $lead->state = $request->state[$i];
            $lead->city = $request->city[$i];

            $lead->entry_date = isset($request->date[$i]) ? date('Y-m-d H:i:s', strtotime($request->date[$i])) : date('Y-m-d H:i:s');         }
        else
        {
            $lead->query_id = $request->query_id ? $request->query_id : null;
            $lead->name = Helper::properCase($request->name);
            $lead->phone = isset($request->phone) ? Helper::properMobile($request->phone) : Helper::properMobile($request->mobile);
            $lead->mobile = isset($request->mobile) ? Helper::properMobile($request->mobile) : Helper::properMobile($request->phone);
            $lead->email = $request->email;
            $lead->country = $request->country;
            $lead->state = $request->state;
            $lead->city = $request->city; 
            $lead->height = isset($request->height) ? $request->height : "";
            $lead->weight = isset($request->weight) ? $request->weight : "";

            $lead->entry_date = isset($request->date) ? date('Y-m-d H:i:s', strtotime($request->date)) : date('Y-m-d H:i:s'); 
        }
            
        $lead->created_by = Auth::user()->employee->name;
        //Lead::checkDND($lead);
        $lead->save();

        return $lead;
    }

    public static function checkDND($lead)
    {
        $dnd = new DND;
        $leadDnd = new LeadDnd;
        $status = 0;
        $leadDnd->lead_id = $lead->id;
        //dd($leadDnd->id."/ ".$leadDnd->mobile_dnd."/ ".$leadDnd->phone_dnd);
        $resultPhone = $dnd->scrub(Helper::properMobile($lead->phone));
       //dd("Phone X".$resultPhone);
       
        if($resultPhone == "Y"){

            //dd("Phone X".$resultPhone);
            $lead->phone_dnd = 1;
            $leadDnd->phone_dnd = 1;
            $status = 1;
            
        } else if($resultPhone == "N") {
           //dd("Phone Y".$resultPhone);
            $lead->phone_dnd = 0;
            $leadDnd->phone_dnd = 0;
        }
        else if($resultPhone == "error")
        {
            //dd("Phone 2".$resultPhone);
            return false;
        }

        if(trim($lead->mobile) != trim($lead->phone))
        {
            $resultMobile = $dnd->scrub(Helper::properMobile($lead->mobile));
            if($resultMobile == "Y"){
                //dd("Mobile ".$resultMobile);
                $lead->mobile_dnd = 1;
                $leadDnd->mobile_dnd = 1;

            } elseif ($resultMobile == "N") {
                //dd("Mobile ".$resultMobile);
                $lead->mobile_dnd = 0;
                $leadDnd->mobile_dnd = 0;
            }
             else if($resultMobile == "error")
            {
               return false;
            }
        }
        else
        {   
            $lead->mobile_dnd = $lead->phone_dnd;
            $leadDnd->mobile_dnd = $leadDnd->phone_dnd;
        }

        $leadDnd->save();
    }

    public static function updateLead($id, $request)
    {
        $lead = Lead::find($id);

        $lead->name         = isset($request->name) ? Helper::properCase($request->name) : Helper::properCase($lead->name);
        
        $lead->dob          = isset($request->dob) ? date('Y-m-d', strtotime($request->dob)) : $lead->dob;
        
        $lead->profession   = isset($request->profession) ? Helper::emptyStringToNull($request->profession) : Helper::emptyStringToNull($lead->profession);
        $lead->company      = isset($request->organization) ? Helper::emptyStringToNull($request->organization) : Helper::emptyStringToNull($lead->company);

        $lead->gender       = isset($request->gender) ? Helper::emptyStringToNull($request->gender) : Helper::emptyStringToNull($lead->gender);
        $lead->height       = isset($request->height) ? Helper::emptyStringToNull($request->height) : Helper::emptyStringToNull($lead->height);
        $lead->weight       = isset($request->weight) ? Helper::emptyStringToNull($request->weight) : Helper::emptyStringToNull($request->weight);
        
        $lead->mobile       = isset($request->mobile) ? Helper::properMobile($request->mobile) : Helper::properMobile($lead->mobile);
        $lead->phone        = isset($request->phone) ? Helper::properMobile($request->phone) : Helper::properMobile($lead->phone);
        $lead->email        = isset($request->email) ? $request->email : $lead->email;
        $lead->email_alt    = isset($request->email_alt) ? $request->email_alt : $lead->email_alt;
        $lead->skype        = isset($request->skype) ? $request->skype : $lead->skype;
        $lead->address      = isset($request->address) ? $request->address : $lead->address;
        $lead->country      = isset($request->country) ? $request->country : $lead->country;
        $lead->state        = isset($request->state) ? $request->state : $lead->state;
        $lead->city         = isset($request->city) ? $request->city : $lead->city;
        $lead->zip          = isset($request->zip) ? $request->zip : $lead->zip;
        $lead->updated_by   = Auth::user()->employee->name;

        $lead->save();

        return $lead;
    }

    public static function addLead($request)
    {
        try 
        {
            //Save New Lead
            $lead = Lead::saveLead($request);

            //Save Lead Source
            $source = LeadSource::saveSource($lead, $request);

            //Save Lead Status
            $status = LeadStatus::saveStatus($lead, 1);

            //Save CRE
            if (trim($request->cre) <> "" || Auth::user()->hasRole('cre')) {
                
                /*if($request->cre == '') {
                    Auth::user()->employee->name;
                }*/

                LeadCre::saveCre($lead, $request->cre);
            }
            
            return $lead;

        } catch (\Illuminate\Database\QueryException $e) {
            return $e;
        }
    }

    public static function updateStatus($id, $status){

        $lead = Lead::find($id);
        $lead->status_id = $status;
        $lead->save();
    }

    public static function updateSource($id, $source){

        $lead = Lead::find($id);
        $lead->source_id = $source;
        $lead->save();
    }

    public static function updateCre($id, $cre, $cre_id = null){
        
        $employee = Employee::where('name', $cre)->first();
        
        $lead = Lead::find($id);
        $lead->cre_name = $cre;
        $lead->cre_id = $employee ? $employee->user->id : null;
        $lead->cre_assigned_at = $employee ? Carbon::now() : null;
        $lead->save();
    }

    public static function getReferenceLeads($start_date, $end_date)
    {
        return Lead::with('patient', 'patient.fee')
                    ->with('cre', 'source', 'source.voice')
                    ->leftJoin('lead_sources AS s', 's.lead_id', '=', 'marketing_details.id')
                    ->leftJoin('marketing_details AS r', 'r.id', '=', 's.referrer_id')
                    ->where('s.source_id', 10)
                    ->whereBetween('s.created_at', array($start_date, $end_date))
                    ->select('marketing_details.*', 's.created_at AS sourced_date', 's.sourced_by', 'r.id AS referrer_id', 'r.name AS referrer_name')
                    ->orderBy('id', 'desc')
                    ->get();
    }

    public static function getLeads($start_date, $end_date)
    {
        return Lead::SELECT('marketing_details.*')
                    ->with('status')
                    ->with('source')
                    ->with('status')
                    ->with('cre')
                    ->with('disposition')
                    ->with('cre')
                    ->with('patient.fee')
                    ->whereBetween('created_at', array($start_date, $end_date))
                    ->orderBy('id', 'DESC')
                    ->limit(env('DB_LIMIT'))
                    ->get();

    }

    public static function getLeadsByUser($user, $start_date, $end_date)
    {
        return Lead::with('sources.master', 'status')
                    ->with(['disposition'=> function($q) use($user) {
                        $q->where('name', $user);
                    }])
                    ->with(['cre'=> function($q) {
                        $q->orderBy('created_at', 'desc');
                    }])
                    /*->whereHas('cre', function($q) use ($user, $start_date, $end_date){
                        $q->whereBetween('created_at', array($start_date, $end_date));
                    })*/
                    ->whereBetween('cre_assigned_at', array($start_date, $end_date))
                    ->where('cre_name', $user)
                    ->where('status_id', '<>', '6')
                    ->limit(env('DB_LIMIT'))
                    ->get();

    }

    public static function getCrePipelineByStatus($cre, $status, $start_date, $end_date) 
    {
        return Lead::select('marketing_details.*')
                    ->with(array('dispositions' => function($q) use ($cre, $start_date, $end_date) {
                        $q->where('name', $cre)
                        ->orderBy('id', 'DESC');
                    }))
                    ->leftJoin(DB::raw('(SELECT * FROM lead_cre A WHERE id = (SELECT MAX(id) FROM lead_cre B WHERE A.lead_id=B.lead_id)) AS c'), function($join) {
                        $join->on('marketing_details.id', '=', 'c.lead_id');
                    })
                    ->whereHas('cre', function($q) use ($cre, $start_date, $end_date){
                        $q->whereBetween('created_at', array($start_date, $end_date));
                    })
                    ->where('cre_name', $cre)
                    ->where('status_id', $status)
                    ->limit(env('DB_LIMIT'))
                    ->get();
    }

    public static function getHotPipelines($start_date, $end_date, $cre = NULL)
    {
        /*$leads =  Lead::select('marketing_details.*', 'd.created_at', 'd.name AS cre', 'd.remarks')
                    ->with('patient', 'patient.fees')
                    ->join('call_dispositions AS d', 'marketing_details.id', '=', 'd.lead_id')
                    ->where('d.disposition_id', 15)  
                    ->whereBetween('d.created_at', array($start_date, $end_date))               
                    ->limit(env('DB_LIMIT'))
                    ->get();*/

        $query = Lead::with('cre', 'patient.fees')

                ->with(array('disposition' => function($q) {
                        $q->where('disposition_id', 15);
                }))

                ->whereHas('dispositions', function($q) use ($start_date, $end_date){
                    $q->whereBetween('callback', array($start_date, $end_date))
                        ->where('disposition_id', 15);
                });

        if($cre) {
            $query = $query->where('cre_name', $cre);
        }

        if(Auth::user()->hasRole('sales_tl')) {
            $users = User::getUsersByRole('cre');
            $users = array_pluck($users, 'name');
            $query = $query->whereIn('cre_name', $users);
        }

        return $query->get();   
    }

    public static function getChannelPerformanceBySource($source, $start_date, $end_date)
    {
        return Lead::select(DB::RAW('count(*) AS cnt'))
                ->whereHas('sources', function($q) use ($source, $start_date, $end_date) {
                    $q->where('source', $source)
                    ->whereBetween('created_at', array($start_date, $end_date));
                })
                ->whereBetween('entry_date', array($start_date, $end_date))
                ->first();
    }
    
    public static function leadCount($start_date = NULL, $end_date = NULL) 
    {
        $start_date = isset($start_date) ? $start_date : date('Y-m-d 0:0:0');
        $end_date = isset($end_date) ? $end_date : date('Y-m-d 23:59:59');

        return Lead::whereBetween('created_at', array($start_date, $end_date))
                    ->count();
    }

    //Status count used in CRE Pipeline
    public static function pipelineCountByCre($cre, $status, $start_date, $end_date)
    {
        return Lead::join(DB::raw('(SELECT * FROM lead_cre A WHERE id = (SELECT MAX(id) FROM lead_cre B WHERE A.lead_id=B.lead_id)) AS c'), function($join) {
                            $join->on('marketing_details.id', '=', 'c.lead_id');
                        })
                        ->whereBetween('c.created_at', array($start_date, $end_date))
                        ->where('cre', $cre)
                        ->where('status_id', $status)
                        ->count();
    }

    public static function setPhoneDNDStatus($lead, $status)
    {
        $lead->phone_dnd = $status;
        $lead->save();
    }

    public static function setMobileDNDStatus($lead, $status)
    {
        $lead->mobile_dnd = $status;
        $lead->save();
    }

    public static function dialerUrl($phone)
    {
        return env('DIALER_URL').'exeAgentName='.Auth::user()->username.'&phoneNumber='.$phone;
    }

    public function hasIncompleteDietCart()
    {
        return $this->carts()
                    ->whereHas('products', function($q) {
                        $q->where('product_category_id', 1);
                    })
                    ->where('status_id', '<=', 4)
                    ->whereNotIn('state_id', [2,3])
                    ->first();
    }

     public function getInternationalLocalTime()
    {
           $this->maskPhone = false;
          if ($this->country!='IN'){ 
            $city = new City;
            $flag = false;
            $countryflag = false;
            $regionflag = false;
            $cityflag = false;
            
            if (!is_null($this->country) && $this->country!='')
                if($city->where('country_code',$this->country)->get()){
                    $city = $city->where('country_code', $this->country);
                    $flag = true;
                    $this->countryflag = true;
                }

            if (!is_null($this->state) && $this->state!='')
                if(strpos($this->state,'.') && $city->where('region_code',trim(explode('.',$this->state)[1]))->get()){
                    $city = $city->where('region_code', trim(explode('.',$this->state)[1]));
                    $flag = true;
                    $this->regionflag = true;
                }
                else
                    $this->regionflag = false;

            
            if(!is_null($this->city) && $this->city!='')
                
                if($city->where('name', $this->city)->get()){
                    $city = $city->where('name', $this->city);                       
                    $flag = true;
                    $this->cityflag = true;
                }
                else
                    $this->$cityflag = false;
            
             

            $cities = City::join('yuwow_alpha_1_0.timezones as t', 't.time_zone_id', '=', 'yuwow_alpha_1_0.cities.timezone');
           
        
            if(!$this->regionflag && $this->cityflag)
                $cities = $cities->where('yuwow_alpha_1_0.cities.name', $this->city);

            if($this->regionflag && !$this->cityflag)
                $cities = $cities->where('yuwow_alpha_1_0.cities.region_code', trim(explode('.',$this->state)[1]));


            if($this->regionflag && $this->cityflag)
                $cities = $cities->where('yuwow_alpha_1_0.cities.region_code', trim(explode('.',$this->state)[1]))
                                 ->where('yuwow_alpha_1_0.cities.name', $this->city);                
        

            if($this->countryflag)
                $cities = $cities->selectRaw("AVG(t.gmt_offset) as avg_gmt")->get();
            else
                $cities = $cities->selectRaw("t.gmt_offset as avg_gmt")->get();
           
            if($cities && $cities->count())
            {   
                $avg_gmt = $cities->first()->avg_gmt;
                $carbon = Carbon::now('UTC');        
                $carbon->addSeconds($avg_gmt*3600);
                $curr_time = $carbon->toTimeString();
            }

            if ($flag && $city->first() && $city->first()->country_code =='IN');      
            else
            {
                if($city->first())
                {
                    
                    $dt = Carbon::now();
                    $dk = date("Y-m-d $curr_time");
                  
                    $dk2 = date('Y-m-d 8:0:0');
                    $dk3 = date('Y-m-d 21:59:59');
                    $to_compare = strtotime($dk);
                    $start_limit =  strtotime($dk2);
                    $end_limit = strtotime($dk3);
                    if($to_compare > $start_limit && $to_compare < $end_limit)
                    {
                        $this->maskPhone = false;
                       // dd($lead->maskPhone);
                    }
                    else
                    {
                        $this->maskPhone = true;
                        //dd($lead->maskPhone);
                    }
                    $this->current_time = $curr_time ;

                }
            }
       }
        return $this->maskPhone;
    }
   
}
