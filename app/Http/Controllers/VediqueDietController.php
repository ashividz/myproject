<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon;
use Auth;

class VediqueDietController extends Controller
{

	protected $menu;

	public function __construct(Request $request)
  {
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

}
