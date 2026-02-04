<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cart;

class WishListController extends Controller
{
    public function getWishlistedProducts()
    {
        $items = Cart::instance("wishlist")->content();
        return view('wishlist',['items'=>$items]);
    }
    
    public function addProductToWishlist(Request $request)
    {
        $wishlist = Cart::instance('wishlist');
    
        // CEK JANGAN SAMPAI DUPLIKAT
        foreach ($wishlist->content() as $item) {
            if ($item->id == $request->id) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Item already in wishlist',
                    'count' => $wishlist->content()->count()
                ]);
            }
        }
    
        // TAMBAH ITEM BARU
        $wishlist->add(
            $request->id,
            $request->name,
            1,
            $request->price
        )->associate('App\Models\Product');
    
        return response()->json([
            'status' => 200,
            'message' => 'Item added to wishlist',
            'count' => $wishlist->content()->count()
        ]);
    }

    public function removeProductFromWishlist(Request $request)
    {
        $rowId = $request->rowId;
        Cart::instance("wishlist")->remove($rowId);
        return redirect()->route('wishlist.list');
    }

    public function clearWishlist()
    {
        Cart::instance("wishlist")->destroy();
        return redirect()->route('wishlist.list');
    }

    public function moveToCart(Request $request)
    {
        $item = Cart::instance('wishlist')->get($request->rowId);
        Cart::instance('wishlist')->remove($request->rowId);
        Cart::instance('cart')->add($item->model->id, $item->model->name,1,$item->model->regular_price)->associate('App\Models\Product');
        return redirect()->route('wishlist.list');
    }
    
}