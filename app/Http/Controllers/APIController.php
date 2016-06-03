<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Region;
use App\Models\City;
use App\Models\Nutritionist;
use App\Models\Voice;
use App\Models\Source;
use App\Models\Channel;
use App\Models\User;
use App\Models\Tag;
use App\Models\Lead;
use App\Models\CallDisposition;
use Auth;
use DB;

class APIController extends Controller
{
    public function getCountryList()
    {
        return Country::select(array('country_code', 'country_name'))
                        ->orderBy('country_name')
                        ->get();
    }

    public function getRegionList(Request $request)
    {
        return DB::SELECT("SELECT region_code, r.region_name from yuwow_alpha_1_0.region_codes AS r
                    LEFT JOIN yuwow_alpha_1_0.countries AS c
                    ON c.country_code LIKE concat('%', r.region_code, '%')
                    WHERE region_code LIKE concat(:country_code, '%')
                    ORDER BY r.region_name",
                    [$request->country_code]
                );
    }


    public function getCityList(Request $request)
    {
        return DB::SELECT("SELECT geonameid AS city_code, asciiname AS city_name FROM yuwow_alpha_1_0.cities
                    WHERE concat(country_code, '.', region_code) = :region_code
                    ORDER BY asciiname",
                    [$request->region_code]
                );
    }

    public function getUsersByRole(Request $request)
    {
        return User::getUsersByRole($request->role);
    }

    

    public function getVoiceList()
    {
        return Voice::get();
    }


    /* jEditable format*/
    public function getVoices() //For Editable
    {
        $json_array = array();

        $voices =  Voice::get();
        

        foreach ($voices as $voice) {
            $json_array[$voice->id] = $voice->name;
        }

        return json_encode($json_array);
    }


    public function getSourceList()
    {
        return Source::orderBy('source_name')->get();
    }

    /* Used for jEditable */

    public function getSources() //For Editable
    {
        $json_array = array();

        $sources =  Source::orderBy('source_name')->get();
        

        foreach ($sources as $source) {
            $json_array[$source->id] = $source->source_name;
        }

        return json_encode($json_array);
    }

    public function getCallbacks(Request $request)
    {
        $start_date = isset($request->start) ? $request->start:  date('Y/m/0 0:0:0');
        $end_date = isset($request->end) ? $request->end : date('Y/m/d 23:59:59');
        $cre = Auth::user()->employee->name;

        $events = array();

        $callbacks =  CallDisposition::with(['lead' => function($q){
                            $q->select('id', 'name');
                        }])
                        ->where('name', $cre)
                        ->whereBetween('callback', [$start_date, $end_date])
                        ->get();

        /*$callbacks = DB::SELECT("SELECT DISTINCT m.id, m.clinic, m.enquiry_no, m.name, m.phone,d.callback FROM marketing_details m
                    LEFT JOIN call_dispositions d
                    ON d.clinic = m.clinic AND d.enquiry_no = m.enquiry_no
                    WHERE d.callback BETWEEN :start_date AND :end_date
                    AND d.name = :cre",
                    [$start_date, $end_date, $cre]);
        //dd($callbacks);*/

        foreach ($callbacks as $callback) {
            $output = array();
            $output['title'] = $callback->lead->name;
            $output['start'] = date('Y-m-d H:i:s', strtotime($callback->callback));
            $output['end'] = date('Y-m-d H:i:s', strtotime("+20 minutes", strtotime($callback->callback)));
            $output['url'] = "/lead/" . $callback->lead_id . "/viewDispositions";
            array_push($events, $output);
        }
        return json_encode($events);
    }

/* For jEditable */
    public function getCres() //For Editable
    {
        $json_array = array();

        $cres =  User::getUsersByRole('cre');        

        foreach ($cres as $cre) {
            $json_array[$cre->name] = $cre->name;
        }

        return json_encode($json_array);
    }

    public function getNutritionists() //For Editable
    {
        $json_array = array();

        $nutritionists =  User::getUsersByRole('nutritionist');
        

        foreach ($nutritionists as $nutritionist) {
            $primary = Nutritionist::getPrimaryPatientCount($nutritionist->name);
            $secondary = Nutritionist::getSecondaryPatientCount($nutritionist->name);
            $json_array[$nutritionist->name] = $nutritionist->name . " (" . $primary->count . " - " . $secondary->count . ")";
        }

        return json_encode($json_array);
    }

    public function getUsers(Request $request) //For Editable
    {
        if ($request->role) {
            $json_array = array();

            $role = $request->role;

            $users =  User::getUsersByRole($role);        

            foreach ($users as $user) {
                $json_array[$user->name] = $user->name;
            }

            return json_encode($json_array);
        }
        
        return User::select('users.id', 'name')
                ->join('employees', 'employees.id', '=', 'emp_id')
                ->orderBy('name')
                ->get();  
    }

    public function getTagList()
    {
        return Tag::get();
    }

    public function dispositions($id, Request $request)
    {
        $dispositions = CallDisposition::where('lead_id', $id);

        if($request->name)
        {
            $dispositions = $dispositions->where('name', $request->name);
        }
        
        $dispositions = $dispositions->orderBy('id', 'desc')->get();

        $content = '';

        foreach ($dispositions as $disposition) {
            $content .= "<b>".date('jS M, Y h:i A', strtotime($disposition->created_at))."</b> (".$disposition->master->disposition_code.") ".$disposition->remarks." - ".$disposition->name."<p>";
        }           
        return $content;
    }
}
