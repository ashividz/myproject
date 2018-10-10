<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Days365;
use DB;
use Carbon;
use Auth;

class VediqueDietController extends Controller
{

	protected $menu;
  protected $daterange;
  protected $start_date;
  protected $end_date;

	public function __construct(Request $request)
  {
    $this->daterange = isset($_POST['daterange']) ? explode("-", $_POST['daterange']) : "";
    $this->start_date = isset($this->daterange[0]) ? date('Y/m/d 0:0:0', strtotime($this->daterange[0])) : date("Y/m/01 0:0:0");
    $this->end_date = isset($this->daterange[1]) ? date('Y/m/d 23:59:59', strtotime($this->daterange[1])) : date('Y/m/d 23:59:59');
    $this->menu = 'VediqueDiet';
  }

  public function addFood(Request $request)
  {

    //dd($request);

    if($request->name)
    {
       $id = DB::connection('VediqueDiet')->table('Food_comparison')->insertGetId(
      ['name'=> $request->name , 'image' => $request->image , 'energy' => $request->energy , 'protein' => $request->protein , 'carb' =>$request->carb, 'fat' =>$request->fat, 'calcium'=>$request->calcium , 'fiber'=>$request->fiber , 'vata'=>$request->vata, 'pitta' =>$request->pitta , 'kapha'=>$request->kapha , 'recommendation'=>$request->recommendation , 'scale'=>$request->scale]
      );
    }
   


    $foods = DB::connection('VediqueDiet')->table('Food_comparison')->get();

   //dd($foods); 

    $data = array(
            'menu'          =>  $this->menu,
            'section'       =>  'addfood',
            'foods'        =>    $foods,
            'i'             =>  '1'
        );

        return view('home')->with($data);
  }

  public function progress(Request $request)
  {

    $noOfDays =  (strtotime($this->end_date) - strtotime($this->start_date)) / (60 * 60 * 24) + 1;
    //dd($noOfDays);
    $days =      Days365::limit($noOfDays)->get();
    $patient =   Patient::find($request->id);

    if($patient)
    {
      $user = DB::connection('VediqueDiet')->table('users')
              ->where('email' , trim($patient->lead->email))
              ->first();
      if($user)
      {

       foreach ($days as $day) {
                    $date = date('Y-m-d ', strtotime("+".$day->day." days", strtotime($this->start_date)));
                    $date_end = date('Y-m-d 23:59:59', strtotime("+".$day->day." days", strtotime($this->start_date)));
                    $date_start = date('Y-m-d 00:00:00', strtotime("+".$day->day." days", strtotime($this->start_date)));
              
                    $day->date = $date;
                    $day->date_end = $date_end;
                    $day->date_start = $date_start;

                    $weight = DB::connection('VediqueDiet')->table('user_weight')
                              ->where('email' , $patient->lead->email)
                              ->where('created_at', '>', $date_start)
                              ->where('created_at', '<', $date_end)
                              ->orderBy('created_at', 'desc')
                              ->first();           

                    if(isset($weight)){
                        $day->weight      = $weight->weight;
                        $day->body_fat    = $weight->fat;
                        $day->muscle_mass = $weight->muscalemass;
                        $day->bone_weight = $weight->bonedensity;
                        $day->hydration   = $weight->hydration;
                    }
                    
                }

                $data = array(

                    'menu'          =>  'patient',
                    'section'       =>  'partials.vediquediet',
                    'patient'       =>  $patient,
                    'start_date'    =>  $this->start_date,
                    'end_date'      =>  $this->end_date,
                    'days'          =>  $days
                );

                return view('home')->with($data);
      }
      return "Vediquediet details not found";
       
    }
    return "Patient not found";
    
  }

}
