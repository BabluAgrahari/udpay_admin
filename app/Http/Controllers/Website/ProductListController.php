<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductListController extends Controller
{
	public function index(Request $request, $category = null)
	{
		$query = Product::query()->with('reviews')->status()->where('pro_section', 'primary');
		$category_data = null;
		if ($category) {
			$category_data = Category::where('slug', $category)->status()->first();
			if ($category_data)
				$query->where('product_category_id', $category_data->id);
		}

		if(Auth::check() && Auth::user()->role == 'customer'){
			$query->where('pro_type', 'primary1');
		}

		if(Auth::check() && Auth::user()->role == 'distributor'){
			$query->whereIn('pro_type', ['primary1', 'rp']);
		}

		$data['products'] = $query->paginate(20);
		$data['category_data'] = $category_data;

		return view('Website.product-list', $data);
	}
}
