<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\ProductCategory;
use Redirect;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::with('products')->get();

        $data = array(
            'categories'    =>  $categories
        );

        return view('settings.product.modals.category')->with($data);
    }

    public function updateName(Request $request)
    {
        if (trim($request->value) == '') {
            return "Error: Cannot be Null";
        }

        $category = ProductCategory::where('name', $request->value)
                        ->where('id', '<>', $request->id)
                        ->first();
        if($category) {
            return "Error: Duplicate Name";
        }

        //return $request;

        $category = ProductCategory::find($request->id);
        $category->name =  $request->value;
        $category->save();

        return $request->value;
    }

    public function updateUnit(Request $request)
    {
        if (trim($request->value) == '') {
            return "Error: Cannot be Null";
        }

        //return $request;

        $category = ProductCategory::find($request->id);
        $category->unit =  $request->value;
        $category->save();

        return $request->value;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:product_categories'
        ]);

        $category= new ProductCategory;
        $category->create($request->all());

        $data = array(
            'message' => 'Product Category <b>'.$request->name.'</b> added', 
            'status' => 'success'
        );

        return back()->with($data);
    }
}