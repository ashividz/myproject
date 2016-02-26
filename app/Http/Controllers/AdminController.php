<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Lead;
use App\Support\Helper;
use Auth;
use DB;
use App\Models\Cod;

class AdminController extends Controller
{
    private $menu;
    
    public function __construct()
    {
        $this->middleware('auth');

        if (!Auth::user()->hasRole('admin')) {
            return "You are not authorized to view this";
            die();
        }

        $this->user = isset(Auth::user()->name) ? Auth::user()->name : "";
        $this->menu = "admin";
    } 

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $data = array(
            'menu'      => $this->menu,
            'section'   => "dashboard"
        );
        return view('admin/home')->with($data);
    }

    public function migrateLeads()
    {
        
        $leads = DB::table('marketing_details1')->take(20000)->skip(40000)->orderBy('id', 'DESC')->get();

        foreach ($leads as $lead) {

            $l = Lead::where('clinic', $lead->clinic)
                    ->where('enquiry_no', $lead->enquiry_no)
                    ->first();

            if (!isset($l)) {
                $l = Lead::where('phone', $lead->phone)
                    ->orWhere('email', $lead->email)
                    ->first();
            }

            if (isset($l)) {
                echo $lead->clinic . "-" .$lead->enquiry_no . " Duplicate<p>";
                continue;
            }

            $ld = new Lead;

            $ld->clinic = $lead->clinic;
            $ld->enquiry_no = $lead->enquiry_no;
            $ld->name   = $lead->name;
            $ld->email = Helper::emptyStringToNull($lead->email);
            $ld->email_alt = $lead->email_alt;
            $ld->mobile = Helper::emptyStringToNull($lead->mobile);
            $ld->phone = Helper::emptyStringToNull($lead->phone);
            $ld->skype = $lead->skype;
            $ld->country = $lead->country;
            $ld->state = $lead->state;
            $ld->city = $lead->city;
            $ld->profession = $lead->profession;
            $ld->gender = $lead->gender;
            $ld->height = $lead->height;
            $ld->weight = $lead->weight;
            $ld->entry_date = isset($lead->entry_date) ? $lead->entry_date : $lead->created_at;
            $ld->created_at = $lead->created_at;
            $ld->updated_at = $lead->updated_at;
            $ld->save();
        }

    }

    public function cod()
    {
        return view('cod');
    }

    public function saveCod(Request $request)
    {
        $filename = $_FILES["file"]["tmp_name"];
        if($_FILES["file"]["size"] > 0)
        {
            $file = fopen($filename, "r");

            while (($data = fgetcsv($file, 10000, ",")) !== FALSE)
            {
                $pin = isset($data[0]) ? $data[0] : '';
                $city = isset($data[1]) ? $data[1] : '';
                $state = isset($data[2]) ? $data[2] : '';
                $pickup = isset($data[3]) ? $data[3] : '';
                $oda = isset($data[4]) ? $data[4] : '';
                $cd = isset($data[5]) ? $data[5] : '';

                $cod = new Cod;

                $cod->pin = $pin;
                $cod->city = $city;
                $cod->state = $state;
                $cod->pickup = strpos($pickup,'Pickup') !== false ? true : false;
                $cod->delivery = strpos($pickup,'Delivery') !== false ? true : false; 
                $cod->oda = strpos($oda,'ODA') !== false ? true : false; 
                $cod->opa = strpos($oda,'OPA') !== false ? true : false; 
                $cod->cod = strpos($cd,'No') === false ? true : false; 
                $cod->save();
                echo($cd . $cod . "<br/>");
            }



        }
    }
}
