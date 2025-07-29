<?php
//use Log;
namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Product\Products;
use App\Models\Product\ProductCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use DB;
use Exception;

class ProductController extends Controller
{
	public function productList()
	{
		try {
			$user =  User::where('user_id', Auth::user()->user_id)->first();
			
			$records = Products::with(['Category', 'Details', 'Gallery'])->where('product_status', 1)->get();
			
			$data = [];
			$fol='products';
			foreach ($records as $record) {
				
				$folder = strtolower($record->pro_section) == 'deals' ? 'deals' : 'products';
				
				if($folder == 'deals'){
					$fol = 'deals/gallery';
				}
				$disnt  = (float)$record->mrp - (float)$record->product_sale_price;
				if (strtolower($record->pro_section) == 'primary') {
					if ($user->isactive1 == 0 && strtolower($record->pro_type) != 'primary1') {
					  Log::info('msg:id',[$record->product_id]);
						continue;
					}
					
				}
				$data[strtolower($record->pro_section)][] =  [
					'id'         => $record->product_id,
					'title'      => $record->product_name,
					'ap'         => $record->ap,
					'rp'         => $record->rp,
					'sv'		 => $record->sv,
					'mrp'        => (float)$record->mrp,
					'sale_price' => (float)$record->product_sale_price,
					'stock'      => $record->stock_status == 'OUT' ? 'OUT OF STOCK' : 'Available',
					'thumbnail'  => !empty($record->product_image) ? 'https://uni-pay.in/uploads/' . $folder . '/' . $record->product_image : "",
					'category'   => [
						'id'     => $record->Category->cat_id ?? '',
						'title'  => $record->Category->cat_name ?? '',
						'image'  => !empty($record->Category->img) ? 'https://uni-pay.in/uploads/category/' . $record->Category->img : null,
					],
					'details' => $record->Details->map(function ($detail) {
						return [
							'id'              => $detail->id,
							'description'     => $detail->details,
							'key_ingredients' => $detail->key_ings,
							'uses'            => $detail->uses,
							'result'          => $detail->result
						];
					}),
					'gallery' => $record->Gallery->map(function ($record) use ($fol) {
						return [
							'image' => 'https://uni-pay.in/uploads/' . $fol . '/' . $record->img,
						];
					}),
					'attributes' => $record->Attributes->map(function ($record) {
						return [
							'id'   => $record->id,
							'type' => $record->att_type,
							'size' => $record->size
						];
					}),
					'offer'        => $record->offer ? 1 : 0,
					'discount_per' => strtolower($record->pro_section)=='deals'?number_format($record->up):round(($disnt / $record->mrp) * 100, 2) . '%',
					'note'         => "5% Extra unipoint discount",
				];
			}

			$categories = ProductCategory::where('cat_status', 1)->get();
			if ($categories->isNotEmpty()) {
				foreach ($categories as $key => $record) {
					$data['categories'][strtolower($record->pro_section)][] = array(
						'id'       => $record->cat_id,
						'image'    => 'https://uni-pay.in/uploads/category/' . $record->img,
						'category_name' => $record->cat_name,

					);
					$data['sequence'][strtolower($record->pro_section)][] = $record->cat_name;
				}
			}

			return $this->recordRes($data);
		} catch (Exception $e) {
			return $this->failRes($e->getMessage());
		}
	}
}
