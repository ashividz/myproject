<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Requests\PatientTestimonialRequest;
use Session;

use App\Models\Patient;
use App\Models\YuWoW\Recipe;
use App\Models\YuWoW\RecipeIngredient;
use App\Models\YuWoW\Ingredient;
use App\Models\YuWoW\SentRecipe;
use App\Models\YuWoW\SentIngredient;
use App\Models\Lead;

use Auth;
use DB;
use Input;
use Mail;

class RecipeController extends Controller
{
   

    public function show($id=null, Request $request)
    {
        //DB::update("UPDATE diet_assign AS f SET patient_id = (SELECT id FROM patient_details p WHERE p.clinic=f.clinic AND p.registration_no=f.registration_no) WHERE patient_id = 0");
        $recipies = Recipe::with('ingredients')->orderBy('recipe_name', 'asc')->get();
        $patient = Patient::with('herbs', 'diets', 'suit')->find($id);
        $sent_recipies = SentRecipe::where('patient_id','=',$id)->get();

        //dd($patient);
        $recipe_code = $request->recipe;

        if(isset($recipe_code))
        {
            $ingredients = RecipeIngredient::where('recipe_code','=',$recipe_code)->get();
            $recipe_selected = Recipe::where('recipe_code','=',$recipe_code)->first();
            $recipe_name = $recipe_selected->recipe_name;
        }
        else
        {
            $ingredients = RecipeIngredient::where('recipe_code','=',$recipies->first()->recipe_code)->get();
            $recipe_selected = $recipies->first();
            $recipe_name = $recipe_selected->recipe_name;
        }
        $units = RecipeIngredient::distinct()->select('ingr_unit')->orderBy('ingr_unit', 'asc')->get();
        $ingredient_items = Ingredient::orderBy('name', 'asc')->get();
        //dd($ingredient_items);
        //dd($ingredients);
         /*$testimonials = PatientTestimonial::where('patient_id', $id)
                    ->orderBy('created_at', 'desc')
                    ->limit(12)
                    ->get();*/ 

        $data = array(
            'menu'          => 'patient',
            'section'       => 'partials.recipies',
            'patient'       =>  $patient,
            'ingredients'   =>  $ingredients,
            'recipies'      =>  $recipies,
            'recipe_selected'      =>  $recipe_selected,
            'units'         =>  $units,
            'ingredient_items' => $ingredient_items,
            'sent_recipies' => $sent_recipies,
            'recipe_code'   =>  $recipe_code,
            'recipe_name'   =>  $recipe_name
        );
         return view('home')->with($data);  
    }

    public function sentRecipe($id, $recipe_id, Request $request)
    {
        
        $recipe = SentRecipe::where('id','=',$recipe_id)->first();
        //dd($recipe);
        $ingredients = SentIngredient::where('patient_id','=',$id)->where('recipe_code','=',$recipe->recipe_code)->get();
        //dd($ingredients);
        $recipe_body = "<table border='0' width='100%' style='margin: 0px auto;border: 1px solid #d9d9d9;font-family: arial' cellspacing='0' cellpadding='0' ><tr><td colspan='3' style='padding: 10px;background: #80ccff;'><h3 style='margin: 0px;color: #444444'>{$recipe->recipe_name}</h3></td></tr>";
        $recipe_body .=   "<tr><td colspan='3' style='padding: 0px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>
                            <table cellspacing='0' cellpadding='0' border='0'>
                                 <tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>Servings</td>
                                 <td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>{$recipe->recipe_servings}</td></tr>
                                 <tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>Calorie</td>
                                 <td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>{$recipe->recipe_calorie}</td></tr>
                                 <tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>Preparation Duration</td>
                                 <td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>{$recipe->recipe_preparation_duration}</td></tr>
                                 <tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>Remarks</td>
                                 <td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>{$recipe->recipe_remarks}</td></tr>
                                 <tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>Notes</td>
                                 <td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>{$recipe->recipe_notes}</td></tr>
                            </table></td></tr>";


        


        $recipe_body .= "<tr><td colspan='3' style='padding: 10px;background: #80ccff;'><h4 style='margin: 0px;color: #444444'>INGREDIENTS</h4></td></tr>";
        foreach($ingredients as $ingredient)
        {
            $recipe_body .=   "<tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>{$ingredient->ingredient_name}</td>
                                 <td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>{$ingredient->quantity}</td>
                                 <td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>{$ingredient->unit}</td></tr>";
        
        }
        $recipe_body .= "</table>";
        $data = array(
                    'recipe_body' =>  $recipe_body
                    );

    return view('patient.partials.sent_recipe')->with($data); 
   
   }
    public function sendRecipe($id, Request $request)
    {

        $item_names = $request->item_name;
        $quantity = $request->quantity;
        $unit = $request->unit;
        $recipe_name = strtoupper($request->recipe_name);
        $recipe_code = trim($request->recipe_code);
        $patient_id = trim($request->patient_id);
        $lead_id = trim($request->lead_id);
        $servings = $request->servings;
        $calorie = $request->calorie;
        $cooking_duration = $request->cooking_duration;
        $preparation_duration = $request->preparation_duration;
        $remarks = $request->remarks;
        $notes = nl2br($request->notes);
        $recipe_img_url = $request->recipe_img_url;

        $lead = Lead::find($lead_id);

        $recipe_body = "<table border='0' width='80%' style='margin: 0px auto;border: 1px solid #d9d9d9;font-family: arial' cellspacing='0' cellpadding='0' ><tr><td colspan='3' style='padding: 10px;background: #80ccff;'><h3 style='margin: 0px;color: #444444'>$recipe_name</h3></td></tr>";
        $recipe_body .=   "<tr><td colspan='3' style='padding: 0px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>
                            <table cellspacing='0' cellpadding='0' border='0'>
                                 <tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>Servings</td>
                                 <td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>{$servings}</td></tr>
                                 <tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>Calorie</td>
                                 <td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>{$calorie}</td></tr>
                                 <tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>Cooking Duration</td>
                                 <td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>{$cooking_duration}</td></tr>
                                 <tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>Preparation Duration</td>
                                 <td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>{$preparation_duration}</td></tr>
                                 <tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>Remarks</td>
                                 <td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>{$remarks}</td></tr>
                                 <tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>Notes</td>
                                 <td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>{$notes}</td></tr>
                            </table></td></tr>";


        


        $recipe_body .= "<tr><td colspan='3' style='padding: 10px;background: #80ccff;'><h4 style='margin: 0px;color: #444444'>INGREDIENTS</h4></td></tr>";
        for($i=0;$i<sizeof($item_names);$i++)
        {
            $recipe_body .=   "<tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>{$item_names[$i]}</td>
                                 <td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>{$quantity[$i]}</td>
                                 <td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #666666'>{$unit[$i]}</td></tr>";
            $sent_ingredient = new SentIngredient;
            $sent_ingredient->patient_id = $patient_id;
            $sent_ingredient->recipe_code = $recipe_code;
            $sent_ingredient->ingredient_name = $item_names[$i];
            $sent_ingredient->quantity = $quantity[$i];
            $sent_ingredient->unit = $unit[$i];
            $sent_ingredient->created_by = Auth::user()->id;
            $sent_ingredient->save();
        }

        $recipe_body .= "</table>";
        $email_body = "<table width='auto' cellspacing='1' cellpadding='1' border='0' style='padding:0 20px;background-color:#fff;'>\n
                        <tbody>\n
                            <tr>\n
                                <td width='100%' style='background-color:#fff'>\n
                                    <table width='100%' style='background-color:#fff' border='0' cellspacing='1' cellpadding='1'>\n
                                        <tbody>\n
                                            <tr>\n
                                                <td width='70%'>\n
                                                    <div>\n
                                                        <h2>Namaste! {$lead->name},</h2>\n
                                                    </div>\n
                                                </td>\n
                                                <td align='center' valign='middle'>\n
                                                    <img src='http://nutrihealthsystems.com/assets/images/logo.jpg' width='200' height='40' alt='' class='CToWUd'>\n
                                                </td>\n
                                            </tr>\n
                                        </tbody>\n
                                    </table>\n
                                </td>\n
                            </tr>\n
                            <tr>\n
                                <td>\n
                                    <p>Thanks for choosing Dr. Shikha’s NutriHealth for your Diet Management at home.</p> 

                                    $recipe_body

                                    <p>Warm Regards, </p>NutriHealth Team\n
                                </td>\n
                            </tr>\n
                            <tr>\n
                                <td>\n
                                    <table width='100%' style='background-color:#fff'>\n
                                        <tbody>\n
                                            <tr>\n
                                                <td align='center' colspan='2'>\n
                                                    <font size='3'><b>Nutri-Health YuWoW App Free Download</b></font>                           </td>\n
                                            </tr> \n
                                            <tr>\n
                                                <td align='right'>\n
                                                    <a href='http://goo.gl/nwzLhF'><img src='http://nutrihealthsystems.com/images/android.jpg'></a>\n
                                                </td>\n
                                                <td align='left'>\n
                                                    <a href='http://goo.gl/BCUqro'><img src='http://nutrihealthsystems.com/images/ios.jpg'></a>\n
                                                </td>\n
                                            </tr> \n
                                        </tbody>\n
                                    </table>\n
                                </td>\n
                            </tr>\n
                            <tr>\n
                                <td>    \n
                                    <div style='margin:20px 0px'>\n
                                        <div style='background-color: #fff4c5;border: 1px solid #e4c94b;padding: 10px 20px;text-align:center'>\n
                                            <b>Disclaimer:</b> The membership fee is non-extendable and the results vary according to the age, genetics, activity and gender and compliance on Diet\n
                                        </div>\n
                                    </div>\n
                                    <div>\n
                                        <table width='100%' cellpadding='10' style='background-color:#515651;color:#ffffff'>\n
                                            <tbody>\n
                                                <tr>\n
                                                    <td><b>Website : </b></td>\n
                                                    <td>\n
                                                        <a href='http://www.nutrihealthsystems.com' style='color:inherit'>http://www.nutrihealthsystems.com</a>\n
                                                    </td>\n
                                                </tr>\n
                                                <tr>\n
                                                    <td><b>Sales number :</b></td>\n
                                                    <td><a href='tel:911146666000' style='color:inherit'>011 - 46666 000</a></td>\n
                                                </tr>\n
                                                <tr>\n
                                                    <td><b>Sales Timings :</b></td>\n
                                                    <td>0930 hrs - 1830 hrs.( Mon- Sat) / 0930 hrs - 1700 hrs ( Sun)</td>\n
                                                </tr>\n
                                                <tr>\n
                                                    <td><b>SMS Short Code:</b></td>\n
                                                    <td>Diet at 54646</td>\n
                                                </tr>\n
                                                <tr>\n
                                                    <td><b>For Feedback or complaint :</b></td>\n
                                                    <td><a href='mailto:customer.care@drshikha.com' style='color:inherit'>customer.care@drshikha.com</a></td>\n
                                                </tr>\n
                                            </tbody>\n
                                        </table>\n
                                    </div>\n
                                </td>\n
                            </tr>\n
                        </tbody>\n
                    </table>";

      

        $sent_recipe = new SentRecipe;
        $sent_recipe->patient_id = $patient_id;
        $sent_recipe->recipe_code = $recipe_code;
        $sent_recipe->recipe_name = $recipe_name;
        $sent_recipe->recipe_servings = $servings;
        $sent_recipe->recipe_calorie = $calorie;
        $sent_recipe->recipe_preparation_duration = $preparation_duration;
        $sent_recipe->recipe_notes = $notes;
        $sent_recipe->recipe_remarks = $remarks;
        $sent_recipe->recipe_img_url  = $recipe_img_url;
        $sent_recipe->created_by = Auth::user()->id;
        $sent_recipe->save();
        
       /*Mail::queue([], [], function($message) use ($recipe_body, $lead)
        {
            $from = 'sales@nutrihealthsystems.com';
            
            $message->to($lead->email, $lead->name)
            ->subject("Recipe")
            ->from($from, 'Nutri-Health Systems' );
             
             $message->setBody($recipe_body, 'text/html');
        });*/
        Session::flash('status', 'Recipe Sent to Patient!');
        return $this->show($id, $request);
    }

   
    
}

