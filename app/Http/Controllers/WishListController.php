<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cart;

class WishListController extends Controller
{
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
    
}