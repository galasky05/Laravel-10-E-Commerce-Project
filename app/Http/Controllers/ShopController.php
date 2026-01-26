<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;


class ShopController extends Controller
{
    public function index()
    {
        $product = Product::orderBy('created_at','DESC')->paginate(12);
        return view('shop',['products'=>$product]);
    }

    public function productDetails($slug)
{
    $product = Product::where('slug', $slug)->first();

    if (!$product) {
        abort(404);
    }

    $rproducts = Product::where('slug', '!=', $slug)
                    ->inRandomOrder()
                    ->take(8)
                    ->get();

    return view('details', [
        'product'   => $product,
        'rproducts' => $rproducts
    ]);
}

}   

