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
        $recipe_body .=   "<tr><td colspan='3' style='padding: 0px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>
                            <table cellspacing='0' cellpadding='0' border='0'>
                                 <tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>Servings</td>
                                 <td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>{$recipe->recipe_servings}</td></tr>
                                 <tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>Calorie</td>
                                 <td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>{$recipe->recipe_calorie}</td></tr>
                                 <tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>Preparation Duration</td>
                                 <td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>{$recipe->recipe_preparation_duration}</td></tr>
                                 <tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>Remarks</td>
                                 <td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>{$recipe->recipe_remarks}</td></tr>
                                 <tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>Notes</td>
                                 <td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>{$recipe->recipe_notes}</td></tr>
                            </table></td></tr>";


        


        $recipe_body .= "<tr><td colspan='3' style='padding: 10px;background: #80ccff;'><h4 style='margin: 0px;color: #444444'>INGREDIENTS</h4></td></tr>";
        foreach($ingredients as $ingredient)
        {
            $recipe_body .=   "<tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>{$ingredient->ingredient_name}</td>
                                 <td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>{$ingredient->quantity}</td>
                                 <td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>{$ingredient->unit}</td></tr>";
        
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
        $patient = Patient::find($patient_id);
        $lead = Lead::find($lead_id);

        $recipe_body = "<table border='0' width='80%' style='margin: 0px auto;border: 1px solid #d9d9d9;font-family: arial' cellspacing='0' cellpadding='0' ><tr><td colspan='3' style='padding: 10px;background: #80ccff;'><h3 style='margin: 0px;color: #444444'>$recipe_name</h3></td></tr>";
        $recipe_body .=   "<tr><td colspan='3' style='padding: 0px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>
                            <table width='100%' cellspacing='0' cellpadding='0' border='0'>
                                 <tr><td style='background: #e6e6e6;padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>Servings</td>
                                 <td style='background: #e6e6e6;padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>{$servings}</td></tr>
                                 <tr><td style='background: #d9d9d9;padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>Calorie</td>
                                 <td style='background: #d9d9d9;padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>{$calorie}</td></tr>
                                 <tr><td style='background: #e6e6e6;padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>Cooking Duration</td>
                                 <td style='background: #e6e6e6;padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>{$cooking_duration}</td></tr>
                                 <tr><td style='background: #d9d9d9;padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>Preparation Duration</td>
                                 <td style='background: #d9d9d9;padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>{$preparation_duration}</td></tr>
                               
                            </table></td></tr>";


        


        $recipe_body .= "<tr><td colspan='3' style='padding: 10px;background: #80ccff;'><h4 style='margin: 0px;color: #444444'>INGREDIENTS</h4></td></tr>";
        for($i=0;$i<sizeof($item_names);$i++)
        {
            if($i%2==0)
                $bak_g = "background: #e6e6e6;";
            else
                $bak_g = "background: #d9d9d9;";
            $recipe_body .=   "<tr><td style='$bak_g padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>{$item_names[$i]}</td>
                                 <td style='$bak_g padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>{$quantity[$i]}</td>
                                 <td style='$bak_g padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>{$unit[$i]}</td></tr>";
            $sent_ingredient = new SentIngredient;
            $sent_ingredient->patient_id = $patient_id;
            $sent_ingredient->recipe_code = $recipe_code;
            $sent_ingredient->ingredient_name = $item_names[$i];
            $sent_ingredient->quantity = $quantity[$i];
            $sent_ingredient->unit = $unit[$i];
            $sent_ingredient->created_by = Auth::user()->id;
            $sent_ingredient->save();
        }
        $recipe_body .= "<tr><td colspan='3' style='background: #e6e6e6;padding: 0px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>
                            <table cellspacing='0' cellpadding='0' border='0'>
                                 
                                 <tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'><b>Preparation Method</b></td>
                                 <td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>{$notes}</td></tr>
                                 <tr><td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'><b>Remarks</b></td>
                                 <td style='padding: 5px;border: 1px solid #d9d9d9;border-right:none;border-top: none;color: #595959'>{$remarks}</td></tr>
                            </table></td></tr>";
        $date  = date('l,  jS F, Y');
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
                                                        <p>Here is your recipe specially designed for you!</p>\n
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

                                    <table width='auto' cellspacing='1' cellpadding='1' border='0' style='padding:0 20px;background-color:#fff;'>\n
                                    <tr>\n
                                        <td><div style='border:1px solid #e4c94b;background-color:#fff4c5;padding:5px 20px;text-align:center;margin-bottom:10px'>\n
                                        <h3>Give the Gift of Health this New Year.</h3>\n
                                        <h4>Get your Near &amp; Dear ones to join our preventive healthcare program &amp; win a free program for yourself!</h4>\n
                                        <a target='_blank' href='http://nutrihealthsystems.com/newsletter/references/form.html'>Click here to leave your references</a>\n
                                        </div>\n
                                        </td>\n
                                    </tr>\n
                                    <tr>\n
                                        <td width='50%' align='left' style='padding:10px 20px'>\n
                                        <div style='width:200px'>\n
                                        <h2 style='background-color:#087b3d;color:White;text-align:center;padding:5px'>$date</h2>\n
                                        </div>\n
                                        <h4>Nutritionist: $patient->nutritionist</h4>\n
                                        </td>\n
                                        <td align='right'>\n
                                            <img src='http://nutrihealthsystems.com/assets/images/nutritionist.png' />\n
                                        </td>\n
                                    </tr>
                                    <tr>\n
                                        <td style='padding:10px 20px' colspan='2'>\n
                                            <em>Gift your friends health this festive season. <a target='_blank' href='http://nutrihealthsystems.com/newsletter/references/form.html'>Refer someone</a> and earn surprise benefits.</em>\n
                                        </td>\n
                                    </tr>\n
                                    </table>\n
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

      //return  $email_body;

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
        
       Mail::queue([], [], function($message) use ($email_body, $lead, $recipe_name)
        {
            $from = 'sales@nutrihealthsystems.com';
            
            $message->to($lead->email, $lead->name)
            ->subject("Nutri-Health Recipe - $recipe_name")
            ->from($from, 'Nutri-Health Systems' );
             
             $message->setBody($email_body, 'text/html');
        }); 
        Session::flash('status', 'Recipe Sent to Patient!');
        return $this->show($id, $request);
    }

   
    
}

