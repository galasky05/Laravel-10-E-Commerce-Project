<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;


class ShopController extends Controller
{
    public function index(Request $request)
{
    $page = $request->query('page', 1);
    $size = $request->query('size', 12);
    $order = $request->query("order", -1);
    
    $o_column = "";
    $o_order = "";
    
    switch($order)
    {
        case 1:
            $o_column = "created_at";
            $o_order = "DESC";
            break;
        case 2:
            $o_column = "created_at";
            $o_order = "ASC";
            break;
        case 3:
            $o_column = "regular_price";
            $o_order = "ASC";
            break;
        case 4:
            $o_column = "regular_price";
            $o_order = "DESC";
            break;
        default:
            $o_column = "id";
            $o_order = "DESC";
    }
    
    $brands = Brand::orderBy("name",'ASC')->get();
    $q_brands = $request->query("brands");
    
    // Query products dengan kondisi yang benar
    $products = Product::query();
    
    // Filter by brands jika ada
    if(!empty($q_brands)) {
        $brand_ids = explode(',', $q_brands);
        $products->whereIn('brand_id', $brand_ids);
    }
    
    // Apply ordering
    if($o_column != "") {
        $products->orderBy($o_column, $o_order);
    } else {
        $products->orderBy('id', 'DESC');
    }
    
    // Paginate
    $products = $products->paginate($size);
    
    return view('shop', [
        'products' => $products,
        'page' => $page,
        'size' => $size,
        'order' => $order,
        'brands' => $brands,
        'q_brands' => $q_brands ?? ''
    ]);
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

