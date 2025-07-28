<?php


namespace App\Http\Controllers\Website;

use App\Models\Product;
use App\Http\Controllers\Controller;


class ProductDetailController extends Controller
{
    public function index($id)
    {
        $product = Product::find($id);
        return view('Website.product_detail', compact('product'));
    }
}
