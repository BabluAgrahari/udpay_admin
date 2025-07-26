<?php
 namespace App\Http\Controllers\Website;

 use App\Http\Controllers\Controller;
 use Illuminate\Http\Request;
 use App\Models\Category;
 use App\Models\Product;
 class HomeController extends Controller
 {
    public function index(){

    // $categories = Category::with('products')
    // ->where('status', 1) // Filter categories by status
    // ->where('parent_id','0')
    // ->where('pro_section','primary')
    // ->whereHas('products', function($query) {
    //     $query->where('status', 1); // Filter products by status
    // })
    // ->get();


    // dd($categories);

        $data['categories'] = Category::status()->where('parent_id','0')->get();
        $category = Category::status()->where('parent_id','0')->where('pro_section','primary')->get();
       // dd($category);

       $products = Product::status()->get();
       $array = []; 
       $featured = [];
       foreach($products as $product){
        if(!empty($product->is_featured) && $product->is_featured ==1){
            $featured[] = $product;
        }

        foreach($category as $cat){
            if($cat->id == $product->product_category_id){
                $catName = strtolower(str_replace(' ','_',$cat->name));
                $array[$catName][] = $product;
            }
        }
    }
    $data['featured_products'] = $featured;
    $data['products'] = $array;
    

      return view('Website.home',$data);
    }
 }