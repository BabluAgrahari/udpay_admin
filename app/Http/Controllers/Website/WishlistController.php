<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;

class WishlistController extends Controller
{

    public function addToWishlist(Request $request)
    {
        try {
            $wishlist = Wishlist::where('product_id', $request->product_id)->where('user_id', Auth::user()->id)->first();
            if ($wishlist) {
                return $this->failMsg('Product already in wishlist');
            }
            $wishlist = new Wishlist();
            $wishlist->product_id = $request->product_id;
            $wishlist->user_id = Auth::user()->id;
            if ($wishlist->save()) {
                return $this->successMsg('Product added to wishlist successfully');
            }
            return $this->failMsg('Something went wrong');
        } catch (\Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function removeWishlist(Request $request)
    {
        try {
            $wishlist = Wishlist::where('id', $request->id)->where('user_id', Auth::user()->id)->first();
            if (!$wishlist) {
                return $this->failMsg('Wishlist not found');
            }
            $wishlist->delete();
            return $this->successMsg('Wishlist removed successfully');
        } catch (\Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }
}
