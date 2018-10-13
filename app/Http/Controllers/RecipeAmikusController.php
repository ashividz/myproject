<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\RecipeAmikus;
use Session;
use Illuminate\Support\Facades\Input;

class RecipeAmikusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $matchThis = ['isApproved' => '0'];
        $RecipeAmikus = RecipeAmikus::where($matchThis)->get();

        $data = array(
            'menu'          =>  'service',
            'section'       =>  'recipeAmikus.templates',
            'RecipeAmikus'  =>  $RecipeAmikus
        );
        return view('addRecipe')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
                'editTxtName' => 'required|min:5|max:1500',
                'editTxtCookingTime' => 'required|min:2|max:1500',
                'editTxtServing' => 'required|numeric',
                'editTxtCalories' => 'required|numeric',
                'editTxtSteps' => 'required|min:3|max:1500',
                'editTxtTips' => 'required|min:3|max:1500',
                'editTxtTag' => 'required',
                'editTxtImg' => 'required',
                'editTxtIngredients' => 'required',
                'editDdlPrakriti' => 'required'
            ],[
                'editTxtName.required' => ' The recipe name field is required.',
                'editTxtName.min' => ' The recipe name must be at least 5 characters.',
                'editTxtName.max' => ' The recipe name may not be greater than 1500 characters.',
                'editTxtCookingTime.required' => ' The Cooking Time field is required.',
                'editTxtCookingTime.min' => ' The Cooking Time must be at least 5 characters.',
                'editTxtCookingTime.max' => ' The Cooking Time may not be greater than 1500 characters.',



                'editTxtServing.required' => ' The Serving field is required.',
                'editTxtCalories.required' => ' The Calories field is required.',
                'editTxtServiuse.numeric' => ' The Serving field must be numeric.',
                'editTxtCalories.numeric' => ' The Calories field must be numeric.',
                'editTxtSteps.required' => ' The Steps field is required.',
                'editTxtSteps.min' => ' The Steps must be at least 5 characters.',
                'editTxtSteps.max' => ' The Steps may not be greater than 1500 characters.',
                'editTxtTips.required' => ' The Tips field is required.',
                'editTxtTips.min' => ' The Tips must be at least 5 characters.',
                'editTxtTips.max' => ' The Tips may not be greater than 1500 characters.',
                'editTxtTag.required' => ' The Tag field is required.',
                'editTxtImg.required' => ' The Img field is required.',
                'editTxtIngredients.required' => 'The Ingredients is required.',
                'editDdlPrakriti.required' => ' The Prakriti is required.',

            ]);
            $RecipeAmikus = new RecipeAmikus;
            
            // $RecipeAmikus->save();
            
            $RecipeAmikus = RecipeAmikus::firstOrNew(array('id' => Input::get('hiddenId')));
            $RecipeAmikus->name = $request->editTxtName;
            $RecipeAmikus->cooking_time = $request->editTxtCookingTime;
            $RecipeAmikus->serving = $request->editTxtServing;
            $RecipeAmikus->calories = $request->editTxtCalories;
            $RecipeAmikus->steps = $request->editTxtSteps;
            $RecipeAmikus->tips = $request->editTxtTips;
            $RecipeAmikus->tag = $request->editTxtTag;
            $RecipeAmikus->image = $request->editTxtImg;
            $RecipeAmikus->ingredients = $request->editTxtIngredients;
            $editDdlPrakriti = $request->input('editDdlPrakriti');
            $editDdlPrakriti = implode(',', $editDdlPrakriti);
            $input = $request->except('editDdlPrakriti');
            $input['editDdlPrakriti'] = $editDdlPrakriti;
            $data = array(
            'menu'          =>  'service',
            'section'       =>  'recipeAmikus.templates',
            'RecipeAmikus'  =>  $RecipeAmikus
        );
            $RecipeAmikus->prakriti = $editDdlPrakriti;
            $RecipeAmikus->save(); 
            return redirect()->back()->with('message', 'DATA SAVED SUCCESSFULLY!'); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activateRecipe($id)
    {
         //$RecipeAmikus = RecipeAmikus::findOrFail($id);
         RecipeAmikus::where('id', $id)->update(array('isApproved' => '1')); 
         return redirect()->back()->with('activatMessage', 'RECORD APPROVED SUCCESSFULLY!');  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $RecipeAmikus = RecipeAmikus::findOrFail($id);


        $this->validate($request,[
                'editTxtName' => 'required|min:5|max:1500',
                'editTxtCookingTime' => 'required|min:2|max:1500',
                'editTxtServing' => 'required|numeric',
                'editTxtCalories' => 'required|numeric',
                'editTxtSteps' => 'required|min:3|max:1500',
                'editTxtTips' => 'required|min:3|max:1500',
                'editTxtTag' => 'required',
                'editTxtImg' => 'required',
                'editTxtIngredients' => 'required',
                'editDdlPrakriti' => 'required'
            ],[
                'editTxtName.required' => ' The recipe name field is required.',
                'editTxtName.min' => ' The recipe name must be at least 5 characters.',
                'editTxtName.max' => ' The recipe name may not be greater than 1500 characters.',
                'editTxtCookingTime.required' => ' The Cooking Time field is required.',
                'editTxtCookingTime.min' => ' The Cooking Time must be at least 5 characters.',
                'editTxtCookingTime.max' => ' The Cooking Time may not be greater than 1500 characters.',



                'editTxtServing.required' => ' The Serving field is required.',
                'editTxtCalories.required' => ' The Calories field is required.',
                'editTxtServing.numeric' => ' The Serving field must be numeric.',
                'editTxtCalories.numeric' => ' The Calories field must be numeric.',
                'editTxtSteps.required' => ' The Steps field is required.',
                'editTxtSteps.min' => ' The Steps must be at least 5 characters.',
                'editTxtSteps.max' => ' The Steps may not be greater than 500 characters.',
                'editTxtTips.required' => ' The Tips field is required.',
                'editTxtTips.min' => ' The Tips must be at least 5 characters.',
                'editTxtTips.max' => ' The Tips may not be greater than 500 characters.',
                'editTxtTag.required' => ' The Tag field is required.',
                'editTxtTag.required' => ' The Img field is required.',
                'editTxtIngredients.required' => 'The Ingredients is required.',
                'editDdlPrakriti.required' => ' The Prakriti is required.',

            ]);

        $input = $request->all();
        $ddlData = implode(',', $input['editDdlPrakriti']);
        

        $RecipeAmikus->update(['name' => $input["editTxtName"], 'cooking_time' => $input["editTxtCookingTime"], 'serving' => $input["editTxtServing"], 'calories' => $input["editTxtCalories"], 'steps' => $input["editTxtSteps"], 'tips' => $input["editTxtTips"], 'tag' => $input["editTxtTag"], 'image' => $input["editTxtImg"], 'ingredients' => $input["editTxtIngredients"], 'prakriti' => $ddlData]);


        Session::flash('info', 'Record updated successfully'); 
        Session::flash('alert-class', 'alert-success');

        return redirect()->back();
        }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
