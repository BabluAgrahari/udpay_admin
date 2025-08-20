<?php
// use Log;
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Products;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use DB;
use Exception;

class CartController extends Controller
{
	public function getCartList(Request $request)
	{
		try {
		$records = $this->cartItems();
		if (empty($records))
			return $this->failRes('Your Cart Found Empty.');

		return $this->recordRes($records);
		} catch (Exception $e) {
			return $this->failRes($e->getMessage());
		}
	}

	public function addToCart(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'product_id' => 'required|numeric',
				'variant_id' => 'nullable|numeric',
				'attribute_id' => 'nullable|numeric',
			]);
			if ($validator->fails())
				return $this->validationRes($validator->errors());

			$userId = Auth::user()?->id ?? '';

			$cartIdentifier = ['user_id' => $userId];
			$existingCart = Cart::where(array_merge($cartIdentifier, ['product_id' => $request->product_id]))->first();
			if ($existingCart) {
				return $this->failRes('Product already in cart');
			}

			$save = new Cart();
			$save->user_id = $userId;

			if (Auth::user()->can('isDistributor')) {
				$save->cart_type = 'ap_shopping';
			} elseif (Auth::user()->can('isCustomer')) {
				$save->cart_type = 'shopping';
			} else {
				$save->cart_type = 'deals';  // deals, shopping, ap_shopping
			}
			$save->product_id = $request->product_id;
			$save->quantity = 1;
			$save->variant_id = $request->variant_id ?? null;
			$save->attribute_id = $request->attribute_id ?? null;
			if (!$save->save())
				return $this->failRes('Something Went wrong, Product not added in cart.');

			$cartItems = $this->cartItems();
			return $this->successRes('Product has been added to cart.',$cartItems);
		} catch (Exception $e) {
			return $this->failRes($e->getMessage());
		}
	}

	public function updateQuantity(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'cart_id' => 'required|numeric',
				'quantity' => 'required|numeric|min:1',
			]);
			if ($validator->fails())
				return $this->validationRes($validator->errors());

			$cartId = $request->cart_id;
			$quantity = $request->quantity;

			$cartItem = Cart::where('id', $cartId)->first();
			if (!$cartItem) {
				return $this->failRes('Cart item not found');
			}
			$cartItem->quantity = $quantity;
			if ($cartItem->save()) {
				$cartItems = $this->cartItems();
				return $this->successRes('Cart Item Quantity updated.',$cartItems);
			}
			return $this->failRes('Quantity not updated');
		} catch (Exception $e) {
			return $this->failRes($e->getMessage());
		}
	}

	public function removeCart($product_id)
	{
		try {
			$cart = Cart::where('product_id',$product_id);
			if (empty($cart))
				return $this->failRes('Invaliad Cart id.');

			$res = $cart->delete();
			if (!$res)
				return $this->failRes('Something Went Wrong, Cart item not removed');

			$cartItems = $this->cartItems();
			if (!empty($cartItems))
				return $this->successRes('Cart Item Removed Successfully.',$cartItems);

			return $this->successRes('Cart Item Removed Successfully.');
		} catch (Exception $e) {
			return $this->failRes($e->getMessage());
		}
	}

	private function cartItems()
	{
		$cart_type = 'deals';
		if (Auth::user()->can('isDistributor')) {
			$cart_type = 'ap_shopping';
		} elseif (Auth::user()->can('isCustomer')) {
			$cart_type = 'shopping';
		}
		$records = Cart::with(['product'])->where('user_id', Auth::user()->id)->where('cart_type', $cart_type)->get();

		if ($records->isEmpty())
			return [];

		$totalQty = 0;
		$totalAmount = 0;
		$totalGst = 0;
		$totalSv = 0;
		$totDiscount = 0;
		$totalNetAmt = 0;
		$totalGross = 0;

		$record = [];
		foreach ($records as $cart) {

			$price = 0;
			if(Auth::user()->can('isDistributor') || Auth::user()->can('isCustomer')){
				$totSv = ($cart->product->sv ?? 0) * ($cart->quantity ?? 0);
				$price = $cart->product->product_sale_price ?? 0;
			}else{
				$price = $cart->product->guest_price ?? 0;
			}
			$totalQty += $cart->quantity ?? 0;
			$totalAmount += $price * $cart->quantity ?? 0;

			$gst = 100 / (100 + ($cart->product->igst ?? 0));
			$gross = $totalAmount * $gst;
			$totalGst += $totalAmount - $gross;
			$totalNetAmt = $gross + $totalGst;
			$totalGross += $gross;
			$totalSv += $totSv ?? 0;

			$record[] = [
				'id' => $cart->id,
				'product_id' => $cart->product_id,
				'quantity' => $cart->quantity,
				'product' => !empty($cart->product) ? $this->field($cart->product) : [],
			];
		}

		$shippingCharge = 0;
		if (($totalNetAmt < 649))
			$shippingCharge = 100;

		$recordData['items'] = $record;
		$recordData['calculation'] = [
			'total_qty' => $totalQty,
			'total_sv' => $totalSv,
			'sub_total' => $totalAmount,
			'total_gst' => $totalGst,
			'total_gross' => $totalGross,
			'shipping_amount' => $shippingCharge,
			'total_net_amount' => $totalNetAmt + $shippingCharge,
			'discount_amount' => $totDiscount,
		];

		return $recordData;
	}

	private function field($product)
	{
		$sv = 0;
		if(Auth::user()->can('isDistributor') || Auth::user()->can('isCustomer')){
			$price = $product->product_sale_price;
			$sv = $product->sv;
		}else{
			$price = $product->guest_price;
		}
		$field = [
			'id' => (int) $product->id ?? 0,
			'product_name' => (string) $product->product_name ?? '',
			'slug_url' => (string) $product->slug_url ?? '',
			'product_category_id' => (int) $product->product_category_id ?? 0,
			'product_image' => (string) $product->product_image ?? '',
			'brand_id' => (int) $product->brand_id ?? 0,
			'product_price' => (float) $product->product_price ?? 0,
			'product_sale_price' => (float) $price,
			'sv' => (float) $sv,
			'mrp' => (float) $product->mrp ?? 0,
			'product_stock' => (int) $product->product_stock ?? 0,
			'product_short_description' => (string) $product->product_short_description ?? '',
			'product_description' => (string) $product->product_description ?? '',
			'no_of_reviews' => (int) $product->reviews->where('status', '1')->count() ?? 0,
			'avg_rating' => (float) $product->reviews->where('status', '1')->avg('rating') ?? 0,
			'is_wishlist' => !empty($product->wishlist) ? 1 : 0,
			'category' => [
				'id' => (int) $product->category->id ?? 0,
				'name' => (string) $product->category->name ?? '',
				'slug' => (string) $product->category->slug ?? '',
				'img' => (string) $product->category->img ?? '',
				'description' => (string) $product->category->description ?? '',
			],
			'created_at' => (string) $product->created_at ?? ''
		];
		return $field;
	}
}
