<?php
// use Log;
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use DB;
use Exception;

class ProductController extends Controller
{
	public function homePageProducts()
	{
		try {
			$user = User::where('user_id', Auth::user()->user_id)->first();

			$categories = Category::where('status', '1')->where('pro_section', 'primary')->get();

			$data = [];

			$data['featured'] = Product::with(['category'])->where('status', '1')->where('is_featured', '1')->limit(10)->get()->map(function ($product) {
				return $this->field($product);
			});

			foreach ($categories as $category) {
				$records = Product::with(['category'])->where('status', '1')->where('product_category_id', $category->id)->limit(10)->get()->map(function ($product) {
					return $this->field($product);
				});
				$data[$category->name] = $records;
			}

			$data = collect($data);

			return $this->recordsRes($data);
		} catch (Exception $e) {
			return $this->failRes($e->getMessage());
		}
	}

	private function field($product)
	{
		$field = [
			'id' => (int) $product->id,
			'product_name' => (string) $product->product_name,
			'slug_url' => (string) $product->slug_url,
			'product_category_id' => (int) $product->product_category_id,
			'product_image' => (string) $product->product_image,
			'brand_id' => (int) $product->brand_id,
			'product_price' => (float) $product->product_price,
			'product_sale_price' => (float) $product->product_sale_price,
			'mrp' => (float) $product->mrp,
			'product_stock' => (int) $product->product_stock,
			'product_short_description' => (string) $product->product_short_description,
			'product_description' => (string) $product->product_description,
			'category' => [
				'id' => (int) $product->category->id,
				'name' => (string) $product->category->name,
				'slug' => (string) $product->category->slug,
				'img' => (string) $product->category->img,
				'description' => (string) $product->category->description,
			],
			'created_at' => (string) $product->created_at
		];
		return $field;
	}

	public function productList(Request $request)
	{
		try {
			$limit = $request->limit ?? 10;
			$page = $request->page ?? 1;
			$skip = ($page - 1) * $limit;

			$query = Product::with(['category'])->where('status', '1');

			if (!empty($request->category_id)) {
				$query->where('product_category_id', $request->category_id);
			}

			if (!empty($request->product_name)) {
				$query->where('product_name', 'like', '%' . $request->product_name . '%');
			}

			$products = $query->limit($limit)->skip($skip)->get()->map(function ($product) {
				return $this->field($product);
			});

			return $this->recordsRes($products);
		} catch (Exception $e) {
			return $this->failRes($e->getMessage());
		}
	}

	public function productDetail($id)
	{
		try {
			$product = Product::with(['category', 'brand', 'images', 'variants', 'details', 'reels','reviews', 'reviews.user'])->where('status', '1')->where('id', $id)->first();

			$product = $this->productField($product);
			return $this->recordRes($product);
		} catch (Exception $e) {
			return $this->failRes($e->getMessage());
		}
	}

	private function productField($product)
	{
		$field = [
			'id' => (int) $product->id??0,
			'product_name' => (string) $product->product_name??'',
			'hsn_code' => (string) $product->hsn_code??'',
			'sku_code' => (string) $product->sku_code??'',
			'slug_url' => (string) $product->slug_url??'',
			'product_category_id' => (int) $product->product_category_id??0,
			'product_image' => (string) $product->product_image??'',
			'brand_id' => (int) $product->brand_id??0,
			'product_price' => (float) $product->product_price??0,
			'product_sale_price' => (float) $product->product_sale_price??0,
			'mrp' => (float) $product->mrp??0,
			'product_stock' => (int) $product->product_stock??0,
			'product_short_description' => (string) $product->product_short_description??'',
			'product_description' => (string) $product->product_description??'',
			'product_min_qty' => (int) $product->product_min_qty??0,
			'igst' => (float) $product->igst??0,
			'is_featured' => $product->is_featured??0,
			'up' => (int) $product->up??0,
			'sv' => (float) $product->sv??0,
			'offer' =>$product->offer??0,
			'offer_date' => (string) $product->offer_date??'',
			'pro_type' => (string) $product->pro_type??'',
			'pro_section' => (string) $product->pro_section??'',
			
			'images' => $product->images->map(function ($image) {
				return [
					'id' => (int) $image->id??0,
					'image' => (string) $image->image??'',
				];
			}),
			'variants' => $product->variants->where('status', '1')->map(function ($variant) {
				return [
					'id' => (int) $variant->id??0,
					'sku' => (string) $variant->sku??'',
					'stock' => (int) $variant->stock??0,
					'variant_name' => (string) $variant->variant_name??'',
					'price' => (float) $variant->price??0,
				];
			}),
			'reels' => $product->reels->where('status', '1')->map(function ($reel) {
				return [
					'id' => (int) $reel->id??0,
					'path' => (string) $reel->path??'',
					'is_video' => $reel->is_video??0,
				];
			}),
			'reviews' => $product->reviews->where('status', '1')->map(function ($review) {
				$d = [
					'id' => (int) $review->id??0,
					'rating' => (float) $review->rating??0,
					'review' => (string) $review->review??'',
					'created_at' => (string) $review->created_at??'',
				];

				if(!empty($review->user)){
					$d['user'] = [
						'id' => (int) $review->user->id??0,
						'name' => (string) $review->user->name??'',
						'image' => (string) $review->user->image??'',
					];
				}
				return $d;
			}),
			'created_at' => (string) $product->created_at
		];

		if(!empty($product->brand)){
			$field['brand'] = [
				'id' => (int) $product->brand->id??0,
				'name' => (string) $product->brand->name??'',
				'slug' => (string) $product->brand->slug??'',
				'img' => (string) $product->brand->img??'',
				'description' => (string) $product->brand->description??'',
			];
		}

		if(!empty($product->category)){
			$field['category'] = [
				'id' => (int) $product->category->id??0,
				'name' => (string) $product->category->name??'',
				'slug' => (string) $product->category->slug??'',
				'img' => (string) $product->category->img??'',
				'description' => (string) $product->category->description??'',
			];
		}
		if(!empty($product->details)){
			$field['details'] = [
				'id' => (int) $product->details->id??0,
				'details' => (string) $product->details->details??'',
				'key_ings' => (string) $product->details->key_ings??'',
				'uses' => (string) $product->details->uses??'',
				'result' => (float) $product->details->result??0,
			];
		}
		return $field;
	}
}
