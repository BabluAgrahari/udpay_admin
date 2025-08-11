<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Models\ProductVariant;
use App\Models\ProductDetail;
use Illuminate\Support\Facades\DB;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use App\Traits\WebResponse;

class ProductController extends Controller
{
    use WebResponse;

    public function index(Request $request)
    {
        try {
           
            $data['categories'] = Category::status()->get();
            $data['brands'] = Brand::status()->get();
            $data['units'] = Unit::status()->get();

            return view('CRM.Product.index', $data);
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function create()
    {
        try {
            $data['categories'] = Category::where('parent_id', 0)->status()->get();
            $data['products'] = Product::where('status', '1')->get();
            $data['brands'] = Brand::status()->get();
            $data['units'] = Unit::status()->get();
           
            return view('CRM.Product.create', $data);
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function store(ProductRequest $request)
    {
        try {
            DB::beginTransaction();

            $product = new Product();
            $product->is_combo = $request->has('is_combo') ? '1' : '0';
            $product->combo_id = $request->combo_id ?? '';
            $product->product_name = trim($request->product_name);
            $product->slug_url = generateSlug($request->product_name, 'uni_products', 'slug_url');
            $product->product_category_id = $request->product_category_id;
            $product->brand_id = $request->brand_id;
            $product->product_price = $request->product_price;
            $product->product_sale_price = $request->product_sale_price;
            $product->mrp = $request->mrp;
            $product->guest_price = $request->guest_price;
            $product->product_stock = $request->product_stock ?? 0;
            $product->unit_id = $request->unit_id;
            $product->product_description = $request->product_description;
            $product->product_short_description = $request->product_short_description;
            $product->product_meta_title = $request->product_meta_title;
            $product->product_meta_keywords = $request->product_meta_keywords;
            $product->meta_description = $request->meta_description;
            $product->product_min_qty = $request->product_min_qty ?? 1;
            $product->igst = $request->igst ?? 0;
            $product->is_featured = $request->has('is_featured') ? '1' : '0';
            $product->on_slider = $request->has('on_slider') ? '1' : '0';
            $product->on_banner = $request->has('on_banner') ? '1' : '0';
            $product->up = $request->up ?? 0;
            $product->sv = $request->sv ?? 0;
            $product->offer = $request->has('offer') ? '1' : '0';
            $product->offer_date = $request->offer_date;
            $product->hsn_code = $request->hsn_code;
            $product->sku_code = $request->sku_code;
            $product->created_by = Auth::id();
            $product->updated_by = Auth::id();
            $product->status = $request->has('status') ? '1' : '0';
            $product->mart_status = $request->has('mart_status') ? '1' : '0';
            $product->pro_type = $request->pro_type ?? 'primary1';
            $product->pro_section = $request->pro_section ?? 'primary';

            // Handle main product image using singleFile helper
            if ($request->hasFile('product_image')) {
                $imagePath = singleFile($request->file('product_image'), 'products');
                if ($imagePath) {
                    $product->product_image = $imagePath;
                }
            }

            if ($product->save()) {
                // Handle additional product images using multiFile helper
                if ($request->hasFile('images')) {
                    $imagePaths = multiFile($request->file('images'), 'products');
                    
                    foreach ($imagePaths as $imagePath) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image' => $imagePath
                        ]);
                    }
                }

                // Handle product variants
                if ($request->has('variants') && is_array($request->variants)) {
                    foreach ($request->variants as $variantData) {
                        if (!empty($variantData['varient_name'])) {
                            ProductVariant::create([
                                'product_id' => $product->id,
                                'sku' => $variantData['sku'] ?? '',
                                'stock' => $variantData['stock'] ?? 0,
                                'variant_name' => $variantData['varient_name'],
                                'price' => $variantData['price'] ?? 0,
                                'status' => isset($variantData['status']) ? '1' : '0'
                            ]);
                        }
                    }
                }

                DB::commit();
                return $this->successMsg('Product created successfully');
            }
            
            DB::rollBack();
            return $this->failMsg('Product not created');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->failMsg($e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $data['product'] = Product::with(['images', 'variants'])->findOrFail($id);
            $data['categories'] = Category::with('children')->where('parent_id','0')->status()->get();
            $data['products'] = Product::where('status','1')->get();
            $data['brands'] = Brand::status()->get();
            $data['units'] = Unit::status()->get();

            return view('CRM.Product.edit', $data);
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function update(ProductRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);
            $product->is_combo = $request->has('is_combo') ? '1' : '0';
            $product->combo_id = $request->combo_id ?? '';
            $product->product_name = trim($request->product_name);
            $product->slug_url = generateSlug($request->product_name, 'uni_products', 'slug_url');
            $product->product_category_id = $request->product_category_id;
            $product->brand_id = $request->brand_id;
            $product->product_price = $request->product_price;
            $product->product_sale_price = $request->product_sale_price;
            $product->mrp = $request->mrp;
            $product->guest_price = $request->guest_price;
            $product->product_stock = $request->product_stock ?? 0;
            $product->unit_id = $request->unit_id;
            $product->product_description = $request->product_description;
            $product->product_short_description = $request->product_short_description;
            $product->product_meta_title = $request->product_meta_title;
            $product->product_meta_keywords = $request->product_meta_keywords;
            $product->meta_description = $request->meta_description;
            $product->product_min_qty = $request->product_min_qty ?? 1;
            $product->igst = $request->igst ?? 0;
            $product->is_featured = $request->has('is_featured') ? '1' : '0';
            $product->on_slider = $request->has('on_slider') ? '1' : '0';
            $product->on_banner = $request->has('on_banner') ? '1' : '0';
            $product->up = $request->up ?? 0;
            $product->sv = $request->sv ?? 0;
            $product->offer = $request->has('offer') ? '1' : '0';
            $product->offer_date = $request->offer_date;
            $product->hsn_code = $request->hsn_code;
            $product->sku_code = $request->sku_code;
            $product->updated_by = Auth::id();
            $product->status = $request->has('status') ? '1' : '0';
            $product->mart_status = $request->has('mart_status') ? '1' : '0';
            $product->pro_type = $request->pro_type ?? 'primary1';
            $product->pro_section = $request->pro_section ?? 'primary';

            // Handle main product image using singleFile helper
            if ($request->hasFile('product_image')) {
                // Delete old image if exists
                if ($product->product_image) {
                    $this->deleteImage($product->product_image);
                }
                
                $imagePath = singleFile($request->file('product_image'), 'products');
                if ($imagePath) {
                    $product->product_image = $imagePath;
                }
            }

            if ($product->save()) {
                // Handle additional product images using multiFile helper
                if ($request->hasFile('images')) {
                    $imagePaths = multiFile($request->file('images'), 'products');
                    
                    foreach ($imagePaths as $imagePath) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image' => $imagePath
                        ]);
                    }
                }

                // Handle product variants
                if ($request->has('variants') && is_array($request->variants)) {
                    // Delete existing variants
                    ProductVariant::where('product_id', $product->id)->delete();
                    
                    // Create new variants
                    foreach ($request->variants as $variantData) {
                        if (!empty($variantData['varient_name'])) {
                            ProductVariant::create([
                                'product_id' => $product->id,
                                'sku' => $variantData['sku'] ?? '',
                                'stock' => $variantData['stock'] ?? 0,
                                'variant_name' => $variantData['varient_name'],
                                'price' => $variantData['price'] ?? 0,
                                'status' => isset($variantData['status']) ? '1' : '0'
                            ]);
                        }
                    }
                }

                DB::commit();
                return $this->successMsg('Product updated successfully');
            }
            
            DB::rollBack();
            return $this->failMsg('Product not updated');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->failMsg($e->getMessage());
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|exists:uni_products,id',
                'status' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return $this->validationMsg($validator->errors());
            }

            DB::beginTransaction();
            
            $product = Product::findOrFail($request->id);
            $product->status = $request->status ? '1' : '0';
            $product->updated_by = Auth::id();

            if ($product->save()) {
                DB::commit();
                return $this->successMsg('Status updated successfully');
            }
            
            DB::rollBack();
            return $this->failMsg('Status not updated');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->failMsg($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $product = Product::findOrFail($id);

            // Delete associated images
            $productImages = ProductImage::where('product_id', $id)->get();
            foreach ($productImages as $image) {
                $this->deleteImage($image->image);
                $image->delete();
            }

            // Delete main product image
            if ($product->product_image) {
                $this->deleteImage($product->product_image);
            }

            // Delete associated variants
            ProductVariant::where('product_id', $id)->delete();

            if ($product->delete()) {
                DB::commit();
                return $this->successMsg('Product deleted successfully');
            }
            
            DB::rollBack();
            return $this->failMsg('Product not deleted');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->failMsg($e->getMessage());
        }
    }

    public function datatable(Request $request)
    {
        try {
            $query = Product::with(['category', 'brand', 'unit']);
            
            // Filtering
            if ($request->filled('search')) {
                $search = trim($request->search);
                $query->where(function ($q) use ($search) {
                    $q->where('product_name', 'like', '%' . $search . '%')
                      ->orWhere('sku_code', 'like', '%' . $search . '%')
                      ->orWhere('hsn_code', 'like', '%' . $search . '%');
                });
            }
            
            // if ($request->filled('category_id')) {
            //     $query->where('product_category_id', $request->category_id);
            // }
            
            // if ($request->filled('brand_id')) {
            //     $query->where('brand_id', $request->brand_id);
            // }
            
            // if ($request->has('status') && $request->status !== '') {
            //     $query->where('status', $request->status);
            // }

            $total = $query->count();

            // Ordering
            $columns = $request->columns ?? [];
            if ($request->order && count($request->order)) {
                foreach ($request->order as $order) {
                    $colIdx = $order['column'] ?? 0;
                    if (isset($columns[$colIdx]['data'])) {
                        $colName = $columns[$colIdx]['data'];
                        $dir = $order['dir'] ?? 'asc';
                        
                        // Only allow ordering on safe columns
                        $allowedColumns = ['product_name', 'sku_code', 'product_price', 'product_sale_price', 'mrp', 'product_stock', 'status', 'created_at'];
                        if (in_array($colName, $allowedColumns)) {
                            $query->orderBy($colName, $dir);
                        }
                    }
                }
            }

            // Pagination
            $start = (int)($request->start ?? 0);
            $length = (int)($request->length ?? 10);
            $length = min($length, 100); // Limit max records per page
            
            $products = $query->skip($start)->take($length)->orderBy('id', 'desc')->get();

            $data = $products->map(function ($product) {
 
                    $statusSwitch = '<div class="form-check form-switch">'
                        . '<input type="checkbox" class="form-check-input status-switch" data-id="' . htmlspecialchars($product->id) . '" ' . ($product->status == '1' ? 'checked' : '') . '>'
                        . '<label class="form-check-label" for="status' . htmlspecialchars($product->id) . '"></label>'
                        . '</div>';
                        
                    return [
                        'id' => $product->id,
                        'image' => $product->product_image ? asset($product->product_image) : null,
                        'product_name' => htmlspecialchars($product->product_name ?? 'N/A'),
                        'sku_code' => htmlspecialchars($product->sku_code ?? 'N/A'),
                        'category' => htmlspecialchars($product->category->name ?? 'N/A'),
                        'brand' => htmlspecialchars($product->brand->name ?? 'N/A'),
                        'unit' => htmlspecialchars($product->unit->unit ?? 'N/A'),
                        'mrp' => number_format($product->mrp ?? 0, 2),
                        'product_sale_price' => number_format($product->product_sale_price ?? 0, 2),
                        'product_stock' => $product->product_stock ?? 0,
                        'status' => $statusSwitch,
                        'created_at' => $product->created_at ? $product->created_at : 'N/A',
                    ];
                
            });

            return response()->json([
                'draw' => (int)($request->draw ?? 1),
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'draw' => (int)($request->draw ?? 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

  
    public function details($id)
    {
        try {
            $data['product'] = Product::findOrFail($id);
            $data['detail'] = ProductDetail::where('product_id', $id)->first();
            
            return view('CRM.Product.details', $data);
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function storeDetail(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:uni_products,id',
                'details' => 'nullable|string',
                'key_ings' => 'nullable|string',
                'uses' => 'nullable|string',
                'result' => 'nullable|string',
                'status' => 'nullable|in:0,1'
            ]);

            if ($validator->fails()) {
                return $this->validationMsg($validator->errors());
            }

            DB::beginTransaction();

            $ingredients = ProductDetail::where('product_id', $request->product_id)->first();

            if ($ingredients) {
                // Update existing record
                $ingredients->details = $request->details;
                $ingredients->key_ings = $request->key_ings;
                $ingredients->uses = $request->uses;
                $ingredients->result = $request->result;
                $ingredients->status = $request->status ?? '1';
                $ingredients->save();
            } else {
                // Create new record
                ProductDetail::create([
                    'product_id' => $request->product_id,
                    'details' => $request->details,
                    'key_ings' => $request->key_ings,
                    'uses' => $request->uses,
                    'result' => $request->result,
                    'status' => $request->status ?? '1'
                ]);
            }

            DB::commit();
            return $this->successMsg('Product details saved successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->failMsg($e->getMessage());
        }
    }

    public function getDetail($productId)
    {
        try {
            $ingredients = ProductDetail::where('product_id', $productId)->first();
            
            if ($ingredients) {
                return response()->json([
                    'success' => true,
                    'data' => $ingredients
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No details found for this product'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function deleteImage($imagePath)
    {
        // Extract the file path from the asset URL
        $path = str_replace(asset(''), '', $imagePath);
        $fullPath = public_path($path);
        
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}