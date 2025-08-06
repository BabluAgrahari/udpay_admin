<?php


namespace App\Http\Controllers\Website;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Models\ProductDetail;

class ProductDetailController extends Controller
{
    public function index($slug)
    {
        try {
            $data['product'] = Product::with(['images', 'category', 'brand', 'unit', 'variants'])
                ->where('slug_url', $slug)
                ->where('status', '1')
                ->first();
            if (!$data['product']) {
                abort(404, 'Product not found');
            }

            $data['frequentlyBoughtTogether'] = Product::with(['images', 'category', 'brand', 'unit', 'variants'])
                ->where('status', '1')
                ->where('id', '!=', $data['product']->id)
                ->inRandomOrder()
                ->limit(3)
                ->get();

            $data['similarProducts'] = Product::with(['images', 'category', 'brand', 'unit', 'variants'])
                ->where('status', '1')
                ->where('id', '!=', $data['product']->id)
                ->inRandomOrder()
                ->limit(10)
                ->get();

            $data['product_details'] = ProductDetail::where('product_id', $data['product']->id)->first();

            return view('Website.detail', $data);
        } catch (\Exception $e) {
            abort(404, 'Product not found');
        }
    }
}
