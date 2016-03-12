<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\ProductOffer;
use Auth;

class ProductOfferController extends Controller
{
    public function index($id)
    {
        $products = Product::orderBy('product_category_id')->get();

        $offers = ProductOffer::where('product_id', $id)->get();

        $data = array(
            'offers'        =>  $offers,
            'products'      =>  $products,
            'product_id'    =>  $id
        );

        return view('settings.product.modals.offer')->with($data);
    }

    public function store(Request $request, $id)
    {
        $this->validate($request, [
            'minimum_quantity'              => 'required',
            'product_offer_id'              => 'required',
            'product_offer_quantity'        => 'required'
        ]);

        $offer = new ProductOffer;

        //$offer->product_id = $id;
        $offer->created_by = Auth::id();
        $offer->create($request->all());

        $data = array(
            'message'       =>  'Product Offer added',
            'status'        =>  'success'
        );

        return back()->with($data);
    }

}