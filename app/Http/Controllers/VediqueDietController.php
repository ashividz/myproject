<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Lead;
use App\Models\User;
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
    $this->nutritionist = isset($request->user) ? $request->user : ''; 
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

  public function saveRecipe(Request $request)
  {
    if($request->name)
    {
       $id = DB::connection('VediqueDiet')->table('Recipe')->insertGetId(
      ['name'=> $request->name , 
      'cooking_time' => $request->cooking_time , 
      'serving' => $request->serving , 
      'calories' => $request->calories , 
      'steps' =>$request->steps, 
      'tips' =>$request->tips, 
      'tag'=>$request->tag , 
      'image'=>$request->image , 
      'ingredients'=>$request->ingredients, 
      'prakriti' =>$request->prakriti]
      );
    }
    
    return redirect("/marketing/addRecipe");
  }

  public function saveProducts(Request $request)
  {

    if($request->name)
    {
      $id = DB::connection('VediqueDiet')->table('Products')->insertGetId(
        ['name'=> $request->name , 
        'quantity' => $request->quantity , 
        'price' => $request->price , 
        'ingredients' => $request->ingredients , 
        'description' =>$request->description, 
        'benefit' =>$request->benefit, 
        'buy_url'=>$request->buy_url , 
        'is_active'=>$request->is_active,
        'image' =>$request->image]
        );

      $vata_dosage = DB::connection('VediqueDiet')->table('Product_Dosage')->insertGetId(
          ['product_id'=> $id,
            'name' => $request->name ,
            'prakriti'=> 'vata',
            'dosage'  =>  $request->vata_dosage]
          );

      $pitta_dosage = DB::connection('VediqueDiet')->table('Product_Dosage')->insertGetId(
            ['product_id'=> $id,
              'name' => $request->name ,
              'prakriti'=> 'pitta',
              'dosage'  =>  $request->pitta_dosage]
            );
            
      $kapha_dosage = DB::connection('VediqueDiet')->table('Product_Dosage')->insertGetId(
              ['product_id'=> $id,
                'name' => $request->name ,
                'prakriti'=> 'kapha',
                'dosage'  =>  $request->kapha_dosage]
              );
    }
    return redirect("/marketing/addProducts");
  }

  public function saveBrunchArticle(Request $request)
  {

    if($request->article_title)
    {
      $id = DB::connection('VediqueDiet')->table('brunch_article')->insertGetId(
        ['article_title'=> $request->article_title , 
        'article_img' => $request->article_img , 
        'article_desc' => $request->article_desc]
        );

      }
      return redirect("/marketing/addBrunchArticle");
    
  }
  
  public function viewBrunchArticle()
  {

    $brunch_articles = DB::connection('VediqueDiet')->table('brunch_article')->get();

    $data = array(
            'menu'          =>  $this->menu,
            'section'       =>  'addBrunchArticle',
            'brunch_articles'      =>  $brunch_articles,
            'i'             =>  '1'
        );

    return view('home')->with($data);
  }

  public function viewProducts()
  {

    $products = DB::connection('VediqueDiet')->table('Products')->get();

    $data = array(
            'menu'          =>  $this->menu,
            'section'       =>  'addProducts',
            'products'      =>  $products,
            'i'             =>  '1'
        );

    return view('home')->with($data);
  }

  public function viewRecipe()
  {

    $recipes = DB::connection('VediqueDiet')->table('Recipe')->get();

    $data = array(
            'menu'          =>  $this->menu,
            'section'       =>  'addRecipe',
            'recipes'        =>  $recipes,
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

  public function surveySummary()
  {

      $score = array("Always"=>20 , "Occasionally" => 10 , "Never"=> 0  , "Delighted"=>40 , "Satisfied" => 30 , "Not Satisfied" => 0 , "Yes" => 20 , "No" => 0 , "May be"=> 10);

      $surveyanswers = DB::connection('VediqueDiet')
                           ->table('survey_answers')
                           ->whereBetween('created_at', array($this->start_date, $this->end_date))
                           ->get();
                          // dd($surveyanswers);

      $email = [];
      
      foreach ($surveyanswers as $surveyanswer) {

          $total_score = 0;

          $total_score = $total_score + $score[$surveyanswer->q1];
          $total_score = $total_score + $score[$surveyanswer->q2];
          $total_score = $total_score + $score[$surveyanswer->q3];
          $total_score = $total_score + $score[$surveyanswer->q4];
        
          $lead  = Lead::where('email' , $surveyanswer->email)
                          ->has('patient.cfee')
                          ->first();


        if($lead)
        {
           $surveyanswer->patient_id = $lead->patient->id;
           $surveyanswer->leadid = $lead->id;
           $surveyanswer->score = $total_score;
           $surveyanswer->doctor = $lead->patient->doctor;
           $surveyanswer->name = $lead->name;
           $surveyanswer->nutritionist = $lead->patient->nutritionist;
        }
        else
        {
          $surveyanswer->name = null;
        }
          
      }

         $data = array(

                    'menu'          =>  'VediqueDiet',
                    'section'       =>  'nutsurvey',
                    'patients'       =>  $surveyanswers,
                    'start_date'    =>  $this->start_date,
                    'end_date'      =>  $this->end_date,
                    'i'             =>   1
                );

                return view('home')->with($data);

      
  }

  Public function vediqueDietUsers()
  {
    $users = DB::connection('VediqueDiet')->table('users')
              ->select('users.email')
              ->join('Diets','Diets.email','=','users.email')
              ->groupBy('Diets.email')
              ->get();
              $email = [] ; 
              foreach ($users as $user) {
                  
                  $email[] = $user->email;
              }


        $users = User::getUsersByRole('nutritionist');

         if($this->nutritionist != '')
        {
            $patients = Patient::where('nutritionist' , $this->nutritionist)
                    ->has('cfee')
                    ->whereHas('lead', function ($query) use($email) {
                        $query->whereIn('email', $email);
                    })->get();
        }
        else
        {
             $patients = Patient::has('cfee')
                    ->whereHas('lead', function ($query) use($email) {
                        $query->whereIn('email', $email);
                    })->get();
        }

        $data = array(
            'menu'          =>      'marketing',
            'section'       =>      'vediquedietuses',
            'users'         =>      $users,
            'patients'      =>      $patients,
            'name'          =>      $this->nutritionist,
            'i'             =>      1
        );

        return view('home')->with($data);
  }

}