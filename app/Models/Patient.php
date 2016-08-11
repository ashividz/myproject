<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Fee;
use App\Models\User;

use DB;
use Auth;

use OwenIt\Auditing\AuditingTrait;

class Patient extends Model
{
    use AuditingTrait;
    
    protected $table = "patient_details";

    protected $fillable = ['blood_group_id', 'rh_factor_id', 'constipation', 'gas', 'water_retention', 'digestion_type', 'allergic', 'wheezing', 'acidity', 'diseases_history', 'energy_level', 'menstural_history', 'bp_high', 'bp_low', 'diagnosis', 'medical_problem', 'previous_weight_loss', 'medical_history', 'sweet_tooth', 'routine_diet', 'special_food_remark'];

    //public $timestamps = false;

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function cfee()
    {
        return $this->hasOne(Fee::class)->where('start_date', '<=', date('Y-m-d'))->where('end_date', '>=', date('Y-m-d'))->latest();
    }

    public function fee()
    {
        return $this->hasOne(Fee::class)->latest();
    }

    public function fees()
    {
        return $this->hasMany(Fee::class)->orderBy('id', 'DESC');
    }

    public function weight()
    {
        return $this->hasOne(PatientWeight::class)->latest();
    }

    public function weights()
    {
        return $this->hasMany(PatientWeight::class)->orderBy('id', 'DESC');
    }


    public function diet()
    {
        return $this->hasOne(Diet::class)->latest('date_assign');
    }

    public function diets()
    {
        return $this->hasMany(Diet::class)->orderBy('date_assign', 'DESC');
    }

    public function primaryNtr() 
    {
        return $this->hasMany(Nutritionist::class, 'patient_id')
                ->where('secondary', 0)->orderBy('id', 'DESC');
    }

    public function secondaryNtr() 
    {
        return $this->hasMany(Nutritionist::class, 'patient_id')
                ->where('secondary', 1)->orderBy('id', 'DESC');
    }

    public function doctor() 
    {
        return $this->hasOne(Doctor::class)->latest();
    }

    public function doctors() 
    {
        return $this->hasMany(Doctor::class, 'patient_id')->orderBy('created_at', 'DESC');
    }

    public function fitness() 
    {
        return $this->hasOne(Fitness::class)->latest();
    }

    public function constitution() 
    {
        return $this->hasOne(Constitution::class);
    }

    public function medical() 
    {
        return $this->hasOne(Medical::class)->latest('date');
    }  

    public function medicals() 
    {
        return $this->hasMany(Medical::class)->orderBy('id', 'desc');
    }    

    public function herbs() 
    {
        return $this->hasMany(PatientHerb::class)->orderBy('id', 'desc');
    }

    public function survey() 
    {
        return $this->hasOne(PatientSurvey::class)->latest();
    }

    public function surveys() 
    {
        return $this->hasMany(PatientSurvey::class)->orderBy('id', 'desc');
    }

    public function tags() 
    {
        return $this->belongsToMany(Tag::class);
    }

    public function note() 
    {
        return $this->hasOne(PatientNote::class)->latest();
    }

    public function notes() 
    {
        return $this->hasMany(PatientNote::class)->orderBy('id', 'desc');
    }

    public function prakriti() 
    {
        return $this->hasOne(PatientPrakriti::class)->latest();
    }

    public function prakritis() 
    {
        return $this->hasMany(PatientPrakriti::class)->orderBy('id', 'desc');
    }

    public function blood_type() 
    {
        return $this->belongsTo(BloodGroup::class, 'blood_group_id');
    }

    public function rh_factor() 
    {
        return $this->belongsTo(RhFactor::class);
    }

    public function suit() 
    {
        return $this->hasOne(Suit::class);
    }

    public function bt()
    {
        return $this->hasOne(PatientBT::class)->latest('id');
    }

    public function bts()
    {
        return $this->hasMany(PatientBT::class)->orderBy('id', 'DESC');
    }

    public function measurement() {
        return $this->hasOne(PatientMeasurement::class)->latest();
    }

    public function measurements() {
        return $this->hasMany(PatientMeasurement::class)->orderBy('id', 'DESC');
    }

     public function lead_cre() 
    {
        return $this->hasMany(LeadCre::class, 'lead_id', 'lead_id');
    }

    public static function getActivePatients($nutritionist = NULL)
    {
        $query =  Patient::select('patient_details.*')
                ->with('lead', 'cfee', 'doctor')
                /*->leftJoin(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                    $join->on('patient_details.id', '=', 'f.patient_id');
                })
                ->where('f.end_date', '>=', DB::RAW('CURDATE()'));*/
                ->whereHas('fee', function($query){
                    $query->where('end_date', '>=', DB::RAW('CURDATE()'));
                })
                ->join('marketing_details as m', 'm.id', '=', 'patient_details.lead_id');

        if($nutritionist) {
            $query = $query->where('nutritionist', $nutritionist);  
        }
        
        //$query .= $query->orderBy('f.end_date', 'DESC');

        return $query
                ->orderBy('name')
                ->get();
    }

    public static function getUpgradeListByNtr($nutritionist, $all = NULL)
    {
        $query = Patient::select("patient_details.*", DB::RAW("DATEDIFF(f.end_date, CURDATE()) AS days"))
                ->with('fees')        
                ->with('lead')
                ->join('fees_details AS f', 'f.patient_id', '=', 'patient_details.id')
                ->where('nutritionist', 'Sneha Singh')
                ->where('f.end_date', '>=', date('Y/m/d'));

            if (!$all) {
                $query = $query->whereRaw(DB::RAW("CURDATE() BETWEEN DATE(DATE_SUB(f.end_date, INTERVAL 30 DAY)) AND DATE(DATE_SUB(f.end_date, INTERVAL 20 DAY))"));
            }

        return $query->get();
    }

    public static function viewProgramEndList($cre = NULL)
    {
        $query = Patient::select("patient_details.*", DB::RAW("DATEDIFF(f.end_date, CURDATE()) AS days"))
                ->with('fees')        
                ->with('lead', 'lead.source')
                ->with(['lead.disposition'=> function($q) use ($cre) {
                        $q->where('name', $cre);
                }])
                ->join(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                        $join->on('patient_details.id', '=', 'f.patient_id');
                    })
                ->join(DB::raw('(SELECT * FROM lead_cre A WHERE id = (SELECT MAX(id) FROM lead_cre B WHERE A.lead_id=B.lead_id)) AS c'), function($join) {
                        $join->on('patient_details.lead_id', '=', 'c.lead_id');
                    })
                ->whereRaw(DB::RAW("CURDATE() BETWEEN DATE(DATE_SUB(f.end_date, INTERVAL 30 DAY)) AND DATE(DATE_SUB(f.end_date, INTERVAL 0 DAY))"));
                
            if ($cre) {
                $query = $query->where('c.cre', $cre);
            }

        return $query->orderBy('days')->get();
    }

    public static function getProgramEnd($start_date, $end_date, $nutritionist =NULL) {

        $query = Patient::select('patient_details.*')
                ->with('lead', 'lead.disposition', 'lead.status', 'lead.cre', 'lead.source')
                ->with('fee','cfee','doctor')
                ->join(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                        $join->on('patient_details.id', '=', 'f.patient_id');
                    })
                ->whereBetween('f.end_date', array($start_date, $end_date));    
        if($nutritionist)
            $query = $query->where('nutritionist',$nutritionist);
        return $query->limit(env('DB_LIMIT'))
                ->get();
    }

    //Marketing Upgrade Leads
    public static function getUpgradeList($days = NULL, $nutritionist = NULL, $programDuration=NULL)
    {
        $days = $days <> NULL ? $days : 1;

        $query =  Patient::select('patient_details.*')
                ->with('fee', 'lead', 'lead.sources', 'lead.source', 'lead.cre')
                ->join(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                        $join->on('patient_details.id', '=', 'f.patient_id');
                    })
                //->leftJoin(DB::raw('(SELECT * FROM lead_sources A WHERE id = (SELECT MAX(id) FROM lead_sources B WHERE A.lead_id=B.lead_id)) AS s'), function($join) {
                  //  $join->on('patient_details.lead_id', '=', 's.lead_id');
                //})
                ->whereRaw(DB::RAW("CURDATE() BETWEEN DATE(DATE_SUB(f.end_date, INTERVAL " . $days . " DAY)) AND DATE(DATE_SUB(f.end_date, INTERVAL 0 DAY))"));
        if ($programDuration) {
            $query = $query->where('f.duration','=',$programDuration);
        }
        if ($nutritionist) {
            $query = $query->where('nutritionist', $nutritionist);
        }
                //->
        return $query->get();
    }

    public function hasTag($name, $requireAll = false)
    {
        if (is_array($name)) {
            foreach ($name as $tagName) {
                $hasTag = $this->hasTag($tagName);
                if ($hasTag && !$requireAll) {
                    return true;
                } elseif (!$hasTag && $requireAll) {
                    return false;
                }
            }
            // If we've made it this far and $requireAll is FALSE, then NONE of the roles were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the roles were found.
            // Return the value of $requireAll;
            return $requireAll;
        } else {
            foreach ($this->tags as $tag) {
                if ($tag->name == $name) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function getRegistrationNo($clinic)
    {
        $patient = Patient::where('clinic', $clinic)
                    ->orderBy('id', 'DESC')
                    ->first();

        return (int) $patient->registration_no + 1;
    }

    public static function getDietNotStarted()
    {        
        
        /*$query = "SELECT p.nutritionist, f.patient_id, f.receipt_no, m.name, f.created_at, f.start_date, f.end_date, f.total_amount, s.remark FROM fees_details f";
        $query .= " LEFT JOIN patient_details p";
        $query .= " ON (p.id = f.patient_id)";
        $query .= " LEFT JOIN suit_ntsuit s";   
        $query .= " ON s.patient_id = f.patient_id"; 
        $query .= " JOIN marketing_details m ON m.id=p.lead_id";  
        $query .= " WHERE f.patient_id NOT IN (SELECT DISTINCT d.patient_id FROM diets d WHERE d.patient_id = f.patient_id AND date_assign >= f.entry_date)";
        $query .= " AND f.end_date >= CURDATE()";
        $query .= " ORDER BY start_date DESC";
        return DB::select($query);*/

        $users = User::getUsersByRole('nutritionist');

        $nutritionistIds = $users->pluck('id')->toArray();
        $nutritionistUserNames   = User::whereIn('id',$nutritionistIds)->get()->pluck('username')->toArray();

        return Patient::select("patient_details.*")
                ->join(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                        $join->on('patient_details.id', '=', 'f.patient_id');
                    })
                ->whereRaw('f.patient_id NOT IN (SELECT DISTINCT d.patient_id FROM diets d WHERE d.patient_id = f.patient_id AND date_assign >= f.entry_date)')
                ->where('f.end_date', '>=', date('Y-m-d'))
                ->with(['lead.dialerphonedisposition' => function($query) use($nutritionistUserNames){
                    $query->with('user')
                    ->whereIn('username',$nutritionistUserNames)
                    ->orderBy('eventdate','desc');
                }])            
                ->with(['lead.dialermobiledisposition' => function($query) use($nutritionistUserNames){
                    $query->with('user')
                    ->whereIn('username',$nutritionistUserNames)
                    ->orderBy('eventdate','desc');
                }])
                ->get();

        
    
    }

    public static function getAppointments($date = NULL)
    {
        if(!$date)
            $date = date('Y-m-d');
        $start_day_time = date('Y-m-d 00:00:00',strtotime($date));
        $end_day_time   = date('Y-m-d 23:59:59',strtotime($date));       

        $totalPatients = DB::table('patient_details')
        ->join('fees_details',function($join) use($date){
            $join->on('fees_details.patient_id', '=', 'patient_details.id')
            ->on('end_date','>=',DB::raw("'$date'"));
        })      
        ->groupBy(DB::raw('ifnull(patient_details.nutritionist,"")'))
        ->select(DB::raw('ifnull(patient_details.nutritionist,"") as nutritionist,count(distinct(patient_details.id)) as totalPatients'))
            ->get();
        
        $todaysAppointments = DB::table('patient_details')
        ->join('fees_details',function($join) use ($date){
            $join->on('fees_details.patient_id', '=', 'patient_details.id')
            ->on('end_date','>=',DB::raw("'$date'"));
        })        
        ->join('marketing_details','marketing_details.id','=','patient_details.lead_id')
        ->leftJoin(DB::raw("(select * from diets where date_assign > '$date' ) as diets"),function($join) use($date,$start_day_time){
            $join->on('patient_details.id','=','diets.patient_id')                
            ->on('diets.date_assign','=',DB::raw("date_add('$date',interval 1 + IFNULL(advance_diet,0) day)"))
            ->on(DB::raw('IFNULL(diets.email,0)'),'=',DB::raw('1'))
            ->on('diets.updated_at','<',DB::raw("'$start_day_time'"));
        })
        ->groupBy(DB::raw('ifnull(patient_details.nutritionist,"")'))
        ->whereNull('diets.patient_id')
        ->select(DB::raw('ifnull(patient_details.nutritionist,"") as nutritionist,count(distinct(patient_details.id)) as todaysAppointments'))
        ->get();

        $currentAppointments = DB::table('patient_details')
        ->join('fees_details',function($join) use ($date){
            $join->on('fees_details.patient_id', '=', 'patient_details.id')
            ->on('end_date','>=',DB::raw("'$date'"));
        })        
        ->leftJoin(DB::raw("(select * from diets where date_assign >'$date' ) as diets"),function($join) use ($date,$end_day_time){
            $join->on('patient_details.id','=','diets.patient_id')                
            ->on('diets.date_assign','=',DB::raw("date_add('$date',interval 1 + IFNULL(advance_diet,0) day)"))
            ->on(DB::raw('IFNULL(diets.email,0)'),'=',DB::raw('1'))
            ->on('diets.updated_at','<=',DB::raw("'$end_day_time'"));
        })
        ->groupBy(DB::raw('ifnull(patient_details.nutritionist,"")'))
        ->whereNull('diets.patient_id')
        ->select(DB::raw('ifnull(patient_details.nutritionist,"") as nutritionist,count(distinct(patient_details.id)) as currentAppointments'))
        ->get();
        
        $diets = DB::table('patient_details')
        ->join('fees_details',function($join) use ($date){
            $join->on('fees_details.patient_id', '=', 'patient_details.id')
            ->on('end_date','>=',DB::raw("'$date'"));
        })        
        ->leftJoin(DB::raw("(select * from diets where date_assign >'$date') as diets"),function($join) use ($date,$start_day_time,$end_day_time){
            $join->on('patient_details.id','=','diets.patient_id')                
            ->on('diets.date_assign','=',DB::raw("date_add('$date',interval 1 + IFNULL(advance_diet,0) day)"))
            ->on(DB::raw('IFNULL(diets.email,0)'),'=',DB::raw('1'))
            ->on('diets.updated_at','>=',DB::raw("'$start_day_time'"))
            ->on('diets.updated_at','<=',DB::raw("'$end_day_time'"));
        })
        ->whereNotNull('diets.patient_id')
        ->groupBy(DB::raw('ifnull(patient_details.nutritionist,"")'))
        ->select(DB::raw('ifnull(patient_details.nutritionist,"") as nutritionist,count(distinct(patient_details.id)) as diets'))
        ->get();           

        //breaks mean diets not sent for more than 7 days
        $breaks = DB::table('patient_details')
        ->join('fees_details',function($join) use ($date){
            $join->on('fees_details.patient_id', '=', 'patient_details.id')
            ->on('end_date','>=',DB::raw("'$date'"))
            ->on('start_date', '<', DB::raw("date_sub('$date',interval 7 day)"));
        })
        ->join('marketing_details','marketing_details.id','=','patient_details.lead_id')        
        ->leftJoin(DB::raw("( select distinct (patient_id ) from diets where (email=1 and date_assign > date_sub('$date',interval 7 day))  group by patient_id) as diets"),function($join){
            $join->on('patient_details.id','=','diets.patient_id');
        })
        ->whereNull('diets.patient_id')
        ->whereRaw('fees_details.patient_id IN (SELECT DISTINCT d.patient_id FROM diets d WHERE d.patient_id = fees_details.patient_id AND date_assign >= fees_details.entry_date)')
        ->groupBy(DB::raw('ifnull(patient_details.nutritionist,"")'))
        ->select(DB::raw('ifnull(patient_details.nutritionist,"") as nutritionist,count(distinct(patient_details.id)) as breaks'))
        ->get(); 

         $dietNotStarted = DB::table('patient_details')
        ->join('fees_details',function($join) use ($date){
            $join->on('fees_details.patient_id', '=', 'patient_details.id')
            ->on('end_date','>=',DB::raw("'$date'"));
        })
        ->join('marketing_details','marketing_details.id','=','patient_details.lead_id')        
        ->whereRaw('fees_details.patient_id NOT IN (SELECT DISTINCT d.patient_id FROM diets d WHERE d.patient_id = fees_details.patient_id AND date_assign >= fees_details.entry_date)')
        ->groupBy(DB::raw('ifnull(patient_details.nutritionist,"")'))
        ->select(DB::raw('ifnull(patient_details.nutritionist,"") as nutritionist,count(distinct(patient_details.id)) as dietNotStarted'))
        ->get();

        /*$calls = DB::table('patient_details')
        ->join('fees_details',function($join){
            $join->on('fees_details.patient_id', '=', 'patient_details.id')
            ->on('end_date','>=',DB::raw('curdate()'));
        })       
        ->join('marketing_details','marketing_details.id','=','patient_details.lead_id')
        ->leftJoin(DB::raw('(select distinct(lead_id) from call_dispositions where created_at >= curdate() and  disposition_id not in (2,3,4,5,6,7,12) or ( disposition_id = 8 and callback >= curdate() and callback < date_add(curdate(),interval 1 day) ) ) as call_dispositions'),function($join){
            $join->on('call_dispositions.lead_id','=','marketing_details.id');
        })
        ->groupBy('patient_details.nutritionist')        
        ->whereNotNull('call_dispositions.lead_id')
        ->select(DB::raw('patient_details.nutritionist as nutritionist,count(distinct(patient_details.id)) as calls'))
        ->get();*/
        $breaks = array_combine(
            array_map(function($o) { return $o->nutritionist; }, $breaks),
            array_map(function($o) { return array('breaks' => $o->breaks); }, $breaks)
        );

        $todaysAppointments = array_combine(
            array_map(function($o) { return $o->nutritionist; }, $todaysAppointments),
            array_map(function($o) { return array('todaysAppointments' => $o->todaysAppointments); }, $todaysAppointments)
        );

        $currentAppointments = array_combine(
            array_map(function($o) { return $o->nutritionist; }, $currentAppointments),
            array_map(function($o) { return array('currentAppointments' => $o->currentAppointments); }, $currentAppointments)
        );

        /*$calls = array_combine(
            array_map(function($o) { return $o->nutritionist; }, $calls),
            array_map(function($o) { return array('calls' => $o->calls); }, $calls)
        );*/

        $diets = array_combine(
            array_map(function($o) { return $o->nutritionist; }, $diets),
            array_map(function($o) { return array('diets' => $o->diets); }, $diets)
        );

        $totalPatients = array_combine(
            array_map(function($o) { return $o->nutritionist; }, $totalPatients),
            array_map(function($o) { return array('totalPatients' => $o->totalPatients); }, $totalPatients)
        );

        $dietNotStarted = array_combine(
            array_map(function($o) { return $o->nutritionist; }, $dietNotStarted),
            array_map(function($o) { return array('dietNotStarted' => $o->dietNotStarted); }, $dietNotStarted)
        );
        
        $app = array_merge_recursive($todaysAppointments,$currentAppointments,$diets,$totalPatients,$breaks,$dietNotStarted);
        //$app = array_merge_recursive($todaysAppointments,$currentAppointments,$calls,$diets,$totalPatients);
        $appointments = array();
        foreach ($app as $nutritionist => $appointment) {
            $obj = (object) [];
            $obj->nutritionist         = $nutritionist;
            $obj->todaysAppointments   = isset($appointment['todaysAppointments']) ? $appointment['todaysAppointments'] : 0;
            $obj->currentAppointments  = isset($appointment['currentAppointments']) ?$appointment['currentAppointments']:0;
            //$obj->calls                = isset($appointment['calls']) ? $appointment['calls'] : 0 ;
            $obj->breaks               = isset($appointment['breaks']) ? $appointment['breaks'] : 0;
            $obj->diets                = isset($appointment['diets']) ? $appointment['diets'] : 0;
            $obj->totalPatients        = isset($appointment['totalPatients']) ? $appointment['totalPatients'] : 0;
            $obj->dietNotStarted       = isset($appointment['dietNotStarted']) ? $appointment['dietNotStarted'] : 0;
            $appointments[]            = $obj;
        }
        return $appointments;
    }

    public static function getYuWoWUsers($nutritionist)
    {
        $yuwowPatients = Patient::select('patient_details.*')
                ->with('lead.yuwow','lead.yuwow.healthtrack','lead.yuwow.diary','lead.yuwow.deviation','lead.yuwow.fitness')
                ->whereHas('fee', function($query){
                    $query->where('end_date', '>=', DB::RAW('CURDATE()'));
                })
                ->join('marketing_details as m', 'm.id', '=', 'patient_details.lead_id')
                ->where('nutritionist', $nutritionist)
                ->get();
        
        return $yuwowPatients;
    }

    public static function register($cart)
    {
        if($cart->lead->patient) {            
            return $patient = $cart->lead->patient;
        }

        if ($cart->hasProductCategories([1])) {
            return Patient::store($cart->lead_id);
        }

        return null; 
    }

    public static function store($lead_id)
    {
        $patient = new Patient;

        $patient->lead_id = $lead_id;
        $patient->created_by = Auth::id();
        $patient->save();

        return $patient;
    }
}