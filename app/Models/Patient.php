<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Fee;

class Patient extends Model
{
    protected $table = "patient_details";

    protected $fillable = ['blood_group_id', 'rh_factor_id', 'constipation', 'gas', 'water_retention', 'digestion_type', 'allergic', 'wheezing', 'acidity', 'diseases_history', 'energy_level', 'menstural_history', 'bp_high', 'bp_low', 'diagnosis', 'medical_problem', 'previous_weight_loss', 'medical_history', 'sweet_tooth', 'routine_diet', 'special_food_remark'];

    //public $timestamps = false;

    public function lead()
    {
    	return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function cfee()
    {
        return $this->hasOne(Fee::class)->where('start_date', '<=', date('Y-m-d'))->latest();
    }

    public function fee()
    {
        return $this->hasOne(Fee::class)->latest();
    }

    public function fees()
    {
        return $this->hasMany(Fee::class)->orderBy('id', 'DESC');
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
        return $this->hasOne(Medical::class);
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

    public function prakritis() 
    {
        return $this->hasMany(PatientPrakriti::class);
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
        return $this->hasOne(PatientBt::class)->latest('id');
    }

    public function bts()
    {
        return $this->hasMany(PatientBt::class)->orderBy('id', 'DESC');
    }

    public function measurement() {
        return $this->hasOne(PatientMeasurement::class)->latest();
    }

    public function measurements() {
        return $this->hasMany(PatientMeasurement::class)->orderBy('id', 'DESC');
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

    public static function getProgramEnd($start_date, $end_date) {

        return Patient::select('patient_details.*')
                ->with('lead', 'lead.disposition', 'lead.status', 'lead.cre', 'lead.source')
                ->with('fee')
                ->join(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                        $join->on('patient_details.id', '=', 'f.patient_id');
                    })
                ->whereBetween('f.end_date', array($start_date, $end_date))
                ->limit(env('DB_LIMIT'))
                ->get();
    }

    //Marketing Upgrade Leads
    public static function getUpgradeList($days = NULL, $nutritionist = NULL)
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

        return Patient::select("patient_details.*")
                ->join(DB::raw('(SELECT * FROM fees_details A WHERE id = (SELECT MAX(id) FROM fees_details B WHERE A.patient_id=B.patient_id)) AS f'), function($join) {
                        $join->on('patient_details.id', '=', 'f.patient_id');
                    })
                ->whereRaw('f.patient_id NOT IN (SELECT DISTINCT d.patient_id FROM diets d WHERE d.patient_id = f.patient_id AND date_assign >= f.entry_date)')
                ->where('f.end_date', '>=', date('Y-m-d'))
                ->get();

        
    
    }
}
