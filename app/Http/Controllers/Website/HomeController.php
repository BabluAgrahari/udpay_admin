<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        try {

            $data['categories'] = Category::status()->where('parent_id', '0')->get();
            $category = Category::status()->where('parent_id', '0')->where('pro_section', 'primary')->get();

            $products = Product::status()->get();
            $array = [];
            $featured = [];
            foreach ($products as $product) {
                if (!empty($product->is_featured) && $product->is_featured == 1) {
                    $featured[] = $product;
                }
            }


            foreach ($category as $cat) {
                foreach ($products as $product) {
                    if ($cat->id == $product->product_category_id) {
                        $catName = strtolower(str_replace(' ', '_', $cat->name));
                        $array[$catName]['category'] = $catName;
                        $array[$catName]['category_id'] = $cat->id;
                        $array[$catName]['category_slug'] = $cat->slug_url;
                        $array[$catName]['category_description'] = $cat->description;
                        $array[$catName]['products'][] = $product;
                    }
                }
            }

            $data['featured_products'] = $featured;
            $data['products'] = $array;
            return view('Website.home', $data);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }
}
