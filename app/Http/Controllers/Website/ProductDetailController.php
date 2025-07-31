<?php


namespace App\Http\Controllers\Website;

use App\Models\Product;
use App\Http\Controllers\Controller;


class ProductDetailController extends Controller
{
    public function index($slug)
    {
        $product = Product::with(['images', 'category', 'brand', 'unit'])
            ->where('slug_url', $slug)
            ->where('status', '1')
            ->first();
        
        if (!$product) {
            abort(404, 'Product not found');
        }
        
        return view('Website.detail', compact('product'));
    }
}
