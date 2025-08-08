<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;

class FrontController extends Controller
{
    //
    public function productList(Request $request, $category=null){
    	
    	$query = Product::query()->with('reviews');
    	$category_data = null;
    	if($category){
    		$category_data = Category::where('slug', $category)->status()->first();
    		if($category_data){
    			$query->where('product_category_id', $category_data->id);

    		}
    		
    	}
    	$query->where('status', 1);
    	$products = $query->paginate(12);
   
    	return view('Website.product-list', compact('products', 'category_data'));
    }
    public function detail(Request $request, $slug){
    	return view('Website.detail');
    }
}
