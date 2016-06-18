<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Cart;
use App\Models\CartProduct;
use Redirect;
use Auth;

class CartProductController extends Controller
{
    public function show($id)
    {        
        $cart = Cart::with('lead')->find($id);

        $categories = ProductCategory::with('products.offers.product')
                    ->with(['products' => function($q){
                        $q->orderBy('duration');
                    }])
                    ->get();


        $data = array(
            'categories'    =>  $categories,
            'cart'          =>  $cart,
        );

        return view('cart.modals.products')->with($data);
    }

    public function store(Request $request, $id)
    {
        $cart = Cart::find($id);
        if ($request->get('product_ids')) {
            foreach($request->get('product_ids') as $key => $value)
            {
                //dd($value);
                $price = $request->input('price.'.$value);
                $coupon = $request->input('coupon.'.$value);
                $discount = $request->input('discount.'.$value);
                $quantity = $request->input('quantity.'.$value);
                $amount = $request->input('amount.'.$value);

                $cartProduct = new CartProduct;
                $cartProduct->cart_id = $id;
                $cartProduct->product_id = $value;
                $cartProduct->price = $price;
                $cartProduct->coupon = $coupon;
                $cartProduct->discount = $discount;
                $cartProduct->quantity = $quantity;
                $cartProduct->amount = $amount;
                $cartProduct->created_by = Auth::id();
                $cartProduct->save(); 

                //dd($cartProduct);

                //Check for Offers
                CartProduct::getOffer($cartProduct);
            }
            //Update the Order Amount
            $cart->updateAmount();
        }
        
        $data = array(
            'message' => 'Successfully Added', 
            'status' => 'success'
        );    

        return redirect('/cart/'.$id)->with($data);
    }

    public function edit($id)
    {
        $product = CartProduct::find($id);

        if(!$product) {
            return "Product not found";
        }

        $data = array(
            'product'         =>  $product
        );

        return view('cart.modals.edit')->with($data);

    }

    public function update(Request $request, $id)
    {
        $cartProduct = CartProduct::find($id);

        $quantity = $cartProduct->quantity;
        $cartProduct->coupon = $request->coupon;
        $cartProduct->quantity = $request->quantity;
        $cartProduct->discount = $request->discount;
        $cartProduct->amount = $request->amount;
        $cartProduct->save();

        $cartProduct->prevQuantity = $quantity;

        //Update the Order Amount
        $cartProduct->cart->updateAmount();

        //Update Offer
        CartProduct::updateOffer($cartProduct);

        $data = array(
            'message' => 'Successfully updated', 
            'status' => 'success'
        );

        return Redirect::to('/cart/'.$cartProduct->cart_id)->with($data);

    }

    public function json(Request $request)
    {
        return Product::whereIn('id', $request->ids)->get();
    }

    public function destroy(Request $request, $id)
    {
        //dd($request->id);
        $cartProduct = CartProduct::find($request->id);

        if ($cartProduct) {
            //Update Offer
            if (!$cartProduct->product_offer_id) {
                CartProduct::deleteOffer($cartProduct);
            }        
            
            CartProduct::destroy($request->id);

            $data = array(
                'message' => 'Successfully deleted', 
                'status' => 'success'
            );
        } else {
            $data = array(
                'message' => 'Product not found', 
                'status' => 'error'
            );
        }
        
            

        //return $data;
        return Redirect::to('/cart/'.$id)->with($data);
    }

    
}