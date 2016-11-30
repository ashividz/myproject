<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\ProductUnit;

class ProductUnitController extends Controller
{
    public function index()
    {
        $units = ProductUnit::get();

        $data = array(
            'units'    =>  $units
        );

        return view('settings.product.modals.unit')->with($data);
    }

    public function update(Request $request)
    {
        if (trim($request->value) == '') {
            return "Error: Cannot be Null";
        }

        $unit = ProductUnit::where('name', $request->value)
                        ->where('id', '<>', $request->id)
                        ->first();
        if($unit) {
            return "Error: Duplicate Name";
        }

        //return $request;

        $unit = ProductUnit::find($request->id);
        $unit->name =  $request->value;
        $unit->save();

        return $request->value;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:product_categories'
        ]);

        $unit= new ProductUnit;
        $unit->create($request->all());

        $data = array(
            'message' => 'Product Category <b>'.$request->name.'</b> added', 
            'status' => 'success'
        );

        return back()->with($data);
    }
}