<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishListController extends Controller
{
    public function getWishlist()
    {
        try {
            $wishlist = Wishlist::with('product', 'product.category', 'product.reviews')->where('user_id', Auth::user()->id)->get()->map(function ($wishlist) {
                return $this->field($wishlist);
            });
            return $this->recordsRes($wishlist);
        } catch (\Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    private function field($wishlist)
    {
        $field = [
            'id' => (int) $wishlist->id,
            'product_id' => (int) $wishlist->product_id,
            'created_at' => (string) $wishlist->created_at
        ];
        if (!empty($wishlist->product)) {
            $price = 0;
            $sv = 0;
            if(Auth::user()->can('isDistributor') || Auth::user()->can('isCustomer')){
                $sv = $wishlist->product->sv;
                $price = $wishlist->product->product_sale_price;
            }else{
                $price = $wishlist->product->guest_price;
                $sv = 0;
            }
            
            $field['product'] = [
                'id' => (int) $wishlist->product->id,
                'product_name' => (string) $wishlist->product->product_name,
                'slug_url' => (string) $wishlist->product->slug_url,
                'product_category_id' => (int) $wishlist->product->product_category_id,
                'product_image' => (string) $wishlist->product->product_image,
                'brand_id' => (int) $wishlist->product->brand_id,
                'product_price' => (float) $wishlist->product->product_price,
                'sv' => (float) $sv,
                'product_sale_price' => (float) $price,
                'mrp' => (float) $wishlist->product->mrp,
                'product_stock' => (int) $wishlist->product->product_stock,
                'product_short_description' => (string) $wishlist->product->product_short_description,
                'product_description' => (string) $wishlist->product->product_description,
                'no_of_reviews' => (int) $wishlist->product->reviews->where('status', '1')->count() ?? 0,
                'avg_rating' => (float) $wishlist->product->reviews->where('status', '1')->avg('rating') ?? 0,
                'category' => [
                    'id' => (int) $wishlist->product->category->id,
                    'name' => (string) $wishlist->product->category->name,
                    'slug' => (string) $wishlist->product->category->slug,
                    'img' => (string) $wishlist->product->category->img,
                    'description' => (string) $wishlist->product->category->description,
                ]
            ];
        }

        return $field;
    }

    public function addToWishlist(Request $request)
    {
        try {
            if (empty($request->product_id)) {
                return $this->failRes('Product id is required');
            }
            $wishlist = Wishlist::where('product_id', $request->product_id)->where('user_id', Auth::user()->id)->first();
            if ($wishlist) {
                return $this->failRes('Product already in wishlist');
            }
            $wishlist = new Wishlist();
            $wishlist->product_id = $request->product_id;
            $wishlist->user_id = Auth::user()->id;
            if ($wishlist->save()) {
                return $this->successRes('Product added to wishlist successfully');
            }
            return $this->failRes('Something went wrong');
        } catch (\Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    public function removeWishlist(Request $request)
    {
        try {
            $wishlist = Wishlist::where('product_id', $request->product_id)->where('user_id', Auth::user()->id)->first();
            if (!$wishlist) {
                return $this->failRes('Wishlist not found');
            }
            $wishlist->delete();
            return $this->successRes('Wishlist removed successfully');
        } catch (\Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }
}
