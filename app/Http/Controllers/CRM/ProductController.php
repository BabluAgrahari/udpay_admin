<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Product::query();

            // Apply filters
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('product_name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('hsn_code', 'like', "%{$search}%");
                });
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            $data['products'] = $query->with('category')->paginate(10);
            $data['categories'] = Category::status()->get();
            $data['brands'] = Brand::status()->get();

            return view('crm.product.index', $data);
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function create()
    {
        try {
            $categories = Category::with('children')->whereNull('parent_id')->status()->get();
            $products = Product::where('status', 1)->get();
            $brands = Brand::status()->get();
            return view('crm.product.create', compact('categories', 'brands', 'products'));
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function store(ProductRequest $request)
    {
        try {
            if ($request->has('variants')) {
                $validator = Validator::make($request->all(), [
                    'variants.stock.*' => 'required|integer|min:0',
                    'variants.attributes.color.*' => 'nullable|string|max:50',
                    'variants.attributes.size.*' => 'nullable|string|max:50'
                ]);

                if ($validator->fails()) {
                    return $this->failMsg($validator->errors()->first());
                }
            }

                $product = new Product();
                $product->category_id = $request->category_id;
                $product->brand_id = $request->brand_id;
                $product->product_name = $request->product_name;
                $product->slug_url = generateSlug($request->product_name, 'products', 'slug_url');
                $product->sku = $request->sku;
                $product->stock = $request->stock;
                $product->hsn_code = $request->hsn_code;
                $product->mrp = $request->mrp;
                $product->sale_price = $request->sale_price;
                $product->cgst = $request->cgst;
                $product->up = $request->up;
                $product->sv = $request->sv;
                $product->offer = $request->offer ? 1 : 0;
                $product->offer_date = $request->offer_date;
                $product->mart_status = $request->mart_status ? 1 : 0;
                $product->product_type = $request->product_type;
                $product->product_section = $request->product_section;
                $product->short_description = $request->short_description;
                $product->description = $request->description;
                $product->meta_title = $request->meta_title;
                $product->meta_keyword = $request->meta_keyword;
                $product->meta_description = $request->meta_description;
                $product->status = $request->status ? 1 : 0;
                $product->is_combo = $request->is_combo ? 1 : 0;
                $product->product_ids = $request->product_ids;
                $product->is_variant = $request->has('variants') ? 1 : 0;

                if ($request->hasFile('images')) {
                    $images = [];
                    foreach ($request->file('images') as $image) {
                        $images[] = singleFile($image, 'products');
                    }
                    $product->images = $images;
                }

                if ($request->hasFile('thumbnail')) {
                    $product->thumbnail = singleFile($request->file('thumbnail'), 'products/thumbnails');
                }

                if ($product->save()) {
                    // Save variants if any
                    if ($request->has('variants')) {
                        $variants = [];
                        foreach ($request->variants['stock'] as $key => $stock) {
                            // Prepare attributes
                            $attributes = [];
                            if (!empty($request->variants['attributes']['color'][$key])) {
                                $attributes['color'] = $request->variants['attributes']['color'][$key];
                            }
                            if (!empty($request->variants['attributes']['size'][$key])) {
                                $attributes['size'] = $request->variants['attributes']['size'][$key];
                            }

                            $savePV = new ProductVariant();
                            $savePV->product_id = $product->_id;
                            $savePV->sku = $request->variants['sku']??'';
                            $savePV->stock = $stock;
                            $savePV->attributes = $attributes;
                            $savePV->status = 1;
                            $savePV->save();  
                        }
                    }

                    return $this->successMsg('Product created successfully');
                }
                return $this->failMsg('Product not created');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $data['product'] = Product::with('variants')->findOrFail($id);
            $data['categories'] = Category::with('children')->whereNull('parent_id')->status()->get();
            $data['products'] = Product::where('status', 1)->get();
            $data['brands'] = Brand::status()->get();

            return view('crm.product.edit', $data);
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function update(ProductRequest $request, $id)
    {
        try {
            if ($request->has('variants')) {
                $validator = Validator::make($request->all(), [
                    'variants.stock.*' => 'required|integer|min:0',
                    'variants.attributes.color.*' => 'nullable|string|max:50',
                    'variants.attributes.size.*' => 'nullable|string|max:50'
                ]);

                if ($validator->fails()) {
                    return $this->failMsg($validator->errors()->first());
                }
            }

                $product = Product::findOrFail($id);
                $product->category_id = $request->category_id;
                $product->brand_id = $request->brand_id;
                $product->product_name = $request->product_name;
                $product->slug_url = generateSlug($request->product_name, 'products', 'slug_url');
                $product->sku = $request->sku;
                $product->stock = $request->stock;
                $product->hsn_code = $request->hsn_code;
                $product->mrp = $request->mrp;
                $product->sale_price = $request->sale_price;
                $product->cgst = $request->cgst;
                $product->up = $request->up;
                $product->sv = $request->sv;
                $product->offer = $request->offer ? 1 : 0;
                $product->offer_date = $request->offer_date;
                $product->mart_status = $request->mart_status ? 1 : 0;
                $product->product_type = $request->product_type;
                $product->product_section = $request->product_section;
                $product->short_description = $request->short_description;
                $product->description = $request->description;
                $product->meta_title = $request->meta_title;
                $product->meta_keyword = $request->meta_keyword;
                $product->meta_description = $request->meta_description;
                $product->status = $request->status ? 1 : 0;
                $product->is_combo = $request->is_combo ? 1 : 0;
                $product->product_ids = $request->product_ids;
                $product->is_variant = $request->has('variants') ? 1 : 0;

                if ($request->hasFile('images')) {
                    $images = [];
                    foreach ($request->file('images') as $image) {
                        $images[] = singleFile($image, 'products');
                    }
                    $product->images = $images;
                }

                if ($request->hasFile('thumbnail')) {
                    $product->thumbnail = singleFile($request->file('thumbnail'), 'products/thumbnails');
                }

                if ($product->save()) {
                    // Handle variants
                    if ($request->has('variants')) {
                        // Delete existing variants
                        ProductVariant::where('product_id', $product->_id)->delete();

                        // Save new variants
                        $variants = [];
                        foreach ($request->variants['stock'] as $key => $stock) {
                            // Prepare attributes
                            $attributes = [];
                            if (!empty($request->variants['attributes']['color'][$key])) {
                                $attributes['color'] = $request->variants['attributes']['color'][$key];
                            }
                            if (!empty($request->variants['attributes']['size'][$key])) {
                                $attributes['size'] = $request->variants['attributes']['size'][$key];
                            }

                            $savePV = new ProductVariant();
                            $savePV->product_id = $product->_id;
                            $savePV->sku = $request->variants['sku']??'';
                            $savePV->stock = $stock;
                            $savePV->attributes = $attributes;
                            $savePV->status = 1;
                            $savePV->save();
                        }
                    } else {
                        ProductVariant::where('product_id', $product->_id)->delete();
                    }

                    return $this->successMsg('Product updated successfully');
                }
                return $this->failMsg('Product not updated');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }


    public function updateStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:products,_id',
                'status' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return $this->failMsg($validator->errors()->first());
            }

            $product = Product::findOrFail($request->id);
            $product->status = (int) $request->status;

            if ($product->save()) {
                return $this->successMsg('Status updated successfully');
            }
            return $this->failMsg('Status not updated');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            // Delete associated variants
            ProductVariant::where('product_id', $product->_id)->delete();

            if ($product->delete()) {
                return $this->successMsg('Product deleted successfully');
            }
            return $this->failMsg('Product not deleted');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }
}