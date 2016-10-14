<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\ProductCategory;

class ProductController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::with('products')->get();

        $data = array(
            'menu'          => 'settings',
            'section'       => 'product.index',
            'categories'    =>  $categories
        );

        return view('home')->with($data);
    }

    public function get()
    {
        return Product::with('category')->get();
    }

    public function getCategories(Request $request = null)
    {
        return ProductCategory::
                    with(['products.offers.product' => function($q) use($request) {
                        if ($request->country=='IN') {
                            $q->selectRaw('products.*, domestic_price_inr as price');
                        } elseif($request->currency=='USD') {
                            $q->selectRaw('products.*, international_price_usd as price');
                        } else {
                            $q->selectRaw('products.*, international_price_inr as price');
                        }                        
                    }])
                    ->with(['products' => function($q) use($request) {
                        if ($request->country=='IN') {
                            $q->selectRaw('products.*, domestic_price_inr as price');
                        } elseif($request->currency=='USD') {
                            $q->selectRaw('products.*, international_price_usd as price');
                        } else {
                            $q->selectRaw('products.*, international_price_inr as price');
                        }
                        
                    }])
                    ->get();
    }

    public function modal($id = null)
    {
        $product = Product::find($id);

        $categories = ProductCategory::get();

        $data = array(
            'categories'    =>  $categories,
            'product'       =>  $product
        );

        return view('settings.product.modals.product')->with($data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'product_category_id'           => 'required',
            'name'                          => 'required|unique:products',
            'domestic_price_inr'            => 'required'
        ]);

        $product = new Product;

        $product->create($request->all());
        
        $data = array(
            'message' => 'New Product <b><i>'.$request->name.'</i></b> added', 
            'status' => 'success'
        );    

        return back()->with($data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'product_category_id'           => 'required',
            'name'                          => 'required|unique:products,name,'.$id,
            'domestic_price_inr'            => 'required'
        ]);

        $product = Product::find($id);

        $product->update($request->all());
        
        $data = array(
            'message' => 'Product <b><i>'.$request->name.'</i></b> updated', 
            'status' => 'success'
        );    

        return back()->with($data);
    }
}