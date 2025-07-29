<?php
//use Log;
namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product\Products;
use App\Models\Product\Cart;
use App\Models\Wallet;
use App\Models\User;
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
				'color_id'   => 'nullable|numeric',
				'size_id'    => 'nullable|numeric',
			]);
			if ($validator->fails())
				return $this->validationRes($validator->message());
			
			$uid = Auth::user()->user_id;
			$product_id = $request->product_id;
			$size_id    = $request->size_id;
			$color_id   = $request->color_id;

			$product = Products::where('product_id', $product_id)->first();

			if ($product->product_status != 1)
				return $this->failRes('product not Found.');


			$cartCount = Cart::where('uid', $uid)->where('pid', $product_id)->where('status', 0)->where('size_id', $size_id)->where('color_id', $color_id)->count();
			if ($cartCount > 0)
				return $this->failRes('This product is already added.');

			$discountAmt   = (strtolower($product->pro_section) == 'primary') ? 0 : $product->up;
			$checkUniPoint = Wallet::where('userid', $uid)->first();
			

			$cart_type = "deals";
			$ap = 0;
			if (strtolower($product->pro_section) == 'primary') {
				//return $this->failRes('Wait for 2 days, Product not added in cart.... ');
				
				$amount   = $product->product_sale_price;
				$totAmt   = $product->product_sale_price;
				$grossAmt = $totAmt - $discountAmt;
				
				$gst      = 100 / ($product->igst + 100);// 0.8474
				$gstAmt   = $grossAmt - ($gst * $grossAmt);//15.26
				$grossAmt = $gst * $grossAmt;
				$net 	  = $gstAmt+$grossAmt;
				

				$cart_type    = "ap_shopping";
				$ap           = $product->sv;
				if (Auth::user()->isactive == 1) {
					$cart_type = "shopping";
					$ap        = $product->sv;
				}
			} else {
				$discountAmt   = ($checkUniPoint->bp >= $discountAmt) ? $discountAmt : $checkUniPoint->bp;
				$amount   = $product->product_sale_price;
				$totAmt   = $product->product_sale_price;
				$net      = $totAmt - $discountAmt;
				$gst      = 100 / ($product->igst + 100);
				$gstAmt   = $net - ($gst * $net);
				$grossAmt = $gst * $net;
			}

			$cart = new Cart();
			$cart->pid       = $product_id;
			$cart->uid       = $uid;
			$cart->qty       = 1;
			$cart->cart_type = $cart_type;
			$cart->up        = $product->up;
			$cart->amt       = $amount;
			$cart->totAmt    = $totAmt;
			$cart->disAmt    = $discountAmt ?? 0;
			$cart->grossAmt  = $grossAmt;
			$cart->netAmt    = $net;
			$cart->gstAmt    = $gstAmt;
			$cart->rp        = $ap;
			$cart->size_id   = $size_id;
			$cart->color_id  = $color_id;
			$cart->status    = 0;
			if (!$cart->save())
				return $this->failRes('Something Went wrong, Product not added in cart.');

			$cartItems = $this->cartItems();
			return $this->recordResMsg($cartItems, 'Product has been added to cart.');
		} catch (Exception $e) {
			return $this->failRes($e->getMessage());
		}
	}

	public function updateQuantity(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'cart_id' => 'required|numeric',
				'type'    => 'required|string|in:increase,decrease'
			]);

			if ($validator->fails())
				return $this->validationRes($validator->messages());

			$cart = Cart::where('id', $request->cart_id)->where('uid', Auth::user()->user_id)->first();
			if (empty($cart))
				return $this->failRes('Invaliad Cart id.');

			if ($cart->qty < 2 && $request->type == 'decrease') {
				Cart::where('id', $request->cart_id)->forceDelete();
				return $this->failRes('Item Quantity less than 1, item has removed removed form Cart.');
			}

			$product = Products::where('product_id', $cart->pid)->first();
			if (empty($product))
				return $this->failRes('Invaliad cart Item.');

			$cart_type = "deals";
			$ap = 0;
			$user_id       = Auth::user()->user_id;
			$userRes 	   = User::where('user_id',$user_id)->first();
			if (strtolower($product->pro_section) == 'primary') {
				$cart_type    = "ap_shopping";
				$ap           = $product->sv;
				if ($userRes->isactive == 1) {
					$cart_type = "shopping";
					$ap        = $product->sv;
				}
			}

			$cartData = Cart::select(DB::raw('SUM(disAmt) as discount'))->where('status', 0)->where('cart_type', $cart_type)->where('uid', Auth::user()->user_id)->where('id', '!=', $request->cart_id)->first();
			$existingDiscount = $cartData->discount ?? 0;
			$qty = 1;

			if ($request->type == 'increase') {
				$qty = (int)$cart->qty + 1;
			} elseif ($request->type == 'decrease') {
				$qty = (int)$cart->qty - 1;
			}

			$discountAmount = (strtolower($product->pro_section) == 'primary') ? 0 : $product->up;
			$wallet         = Wallet::select('bp')->where('userid', Auth::user()->user_id)->first();
			$discountAmount = ($wallet->bp >= ($existingDiscount + $discountAmount)) ? $discountAmount * $qty : $wallet->bp - $existingDiscount;

			$totalAmount = $cart->amt * $qty;
			$netAmt      = $totalAmount - $discountAmount;
			$gst         = 100 / ($product->igst + 100);//0.8474
			$gstAmt      = $netAmt - ($gst * $netAmt);

			$grossAmt = $gst * $netAmt;
			if (strtolower($product->pro_section) == 'primary') {
				$discountAmount = 0;
				$grossAmt = $totalAmount * $gst;
				$gstAmt   = $totalAmount - $grossAmt;
				$netAmt   = $totalAmount;
			}

			$cart->qty       = $qty;
			$cart->rp        = $ap * $qty;
			$cart->totAmt    = $totalAmount;
			$cart->disAmt    = $discountAmount;
			$cart->grossAmt  = $grossAmt;
			$cart->netAmt    = $netAmt;
			$cart->gstAmt    = $gstAmt;

			if (!$cart->save())
				return $this->failRes('Something went wrong, Cart Quantity not updated.');

			$cartItems = $this->cartItems();
			return $this->recordResMsg($cartItems, 'Cart Quantity updated.');
		} catch (Exception $e) {
			return $this->failRes($e->getMessage());
		}
	}

	public function removeCart($cart_id)
	{
		try {
			$cart = Cart::find($cart_id);
			if (empty($cart))
				return $this->failRes('Invaliad Cart id.');

			$res = $cart->delete();
			if (!$res)
				return $this->failRes('Something Went Wrong, Cart item not removed');

			$cartItems = $this->cartItems();
			if (!empty($cartItems))
				return $this->recordResMsg($cartItems, 'Cart Item Removed Successfully.');

			return $this->successRes('Cart Item Removed Successfully.');
		} catch (Exception $e) {
			return $this->failRes($e->getMessage());
		}
	}

	private function cartItems()
	{
		$records = Cart::with(['Product', 'Color', 'Size'])->where('uid', Auth::user()->user_id)->where('status', '0')->get();

		if ($records->isEmpty())
			return [];

		$totalQty       = 0;
		$totalAmount    = 0;
		$totalDiscount  = 0;
		$totalGross     = 0;
		$totalGstAmt    = 0;
		$totalNetAmt    = 0;
		$totalOrderMrp  = 0;

		$totalQty_      = 0;
		$totalAmount_   = 0;
		$totalDiscount_ = 0;
		$totalGross_    = 0;
		$totalGstAmt_   = 0;
		$totalNetAmt_   = 0;
		$totalOrderMrp_ = 0;
		$maximum       = 0;

		$record = [];

		foreach ($records as $cart) {
			$cart_type   = $cart->cart_type == 'deals' ? 'deals' : 'primary';
			// $pro_dtl = Products::where('product_id', $cart->pid)->first();
			$fol   = $cart->cart_type == 'deals' ? 'deals' : 'products';
			$record[$cart_type][] = array(
				'id'            => $cart->id,
				'product_id'    => $cart->pid,
				'product_name'  => $cart->Product->product_name ?? "",
				'image'         => !empty($cart->Product->product_image) ? 'https://uni-pay.in/uploads/' . $fol . '/' . $cart->Product->product_image : '',
				'quantity'      => (int)$cart->qty,
				'amount'        => round((int)$cart->qty * $cart->amt, 2),
				'gstAmt'        => round($cart->gstAmt, 2),
				'netAmt'        => ($cart_type == 'deals')?round((int)$cart->qty * $cart->amt, 2):round($cart->netAmt, 2),
				'mrp'           => (float)$cart->Product->mrp ?? 0,
				'size'          => $cart->Size->size ?? "",
				'color'         => $cart->Color->size ?? "",
				'date'          => strtotime($cart->added_on),
			);

			if ($cart->cart_type == 'deals') {
				$up = $cart->Product->up??0;
				$max = (int)$up*(int)$cart->qty;
				$totalQty        += $cart->qty;
				$maximum         += $max;
				$totalAmount     += round((int)$cart->qty * $cart->amt, 2);
				$totalDiscount   += round($cart->disAmt, 2);
				$totalGross      += round($cart->grossAmt, 2);
				$totalGstAmt     += round($cart->gstAmt, 2);
				$totalNetAmt     += round($cart->netAmt, 2);
				$totalOrderMrp   += (float)$cart->Product->mrp * (int)$cart->qty;
			} else {
				$totalQty_        += $cart->qty;
				$totalAmount_     += round((int)$cart->qty * $cart->amt, 2);
				$totalDiscount_   += round($cart->disAmt, 2);
				$totalGross_      += round($cart->grossAmt, 2);
				$totalGstAmt_     += round($cart->gstAmt, 2);
				$totalNetAmt_     += round($cart->netAmt, 2);
				$totalOrderMrp_   += (float)$cart->Product->mrp * (int)$cart->qty;
			}
		}
		
		 $walletRes = Wallet::select('bp')->where('userid', Auth::user()->user_id)->first();
		 $aftDis = $totalAmount - $walletRes->bp;
		 if ($walletRes->bp >= $totalAmount)
		 	$aftDis = $totalAmount - $maximum;
		
		$record['totals']['deals'] = array(
			'totalQty'        => (int)$totalQty,
			'totalAmt'        => (float)$totalAmount,
			'totalGross'      => round($totalGross, 2),
			'totalGST'        => round($totalGstAmt, 2),
			'totalNetAmt'     => round($totalAmount, 2),
			'totalOrderMrp'   => (float)$totalOrderMrp,
			'totalSp'         => (float)$totalAmount,
			'offDis'          => 0,
			'unipointDis'     => 0,
			'totalDiscount'   => (float)$totalDiscount,
			'maximumDis'      => $maximum,
			'afterDis'       => round($aftDis, 2)
		);

		$record['totals']['primary'] = array(
			'totalQty'        => (int)$totalQty_,
			'totalAmt'        => (float)$totalAmount_,
			'totalGross'      => round($totalGross_, 2),
			'totalGST'        => round($totalGstAmt_, 2),
			'totalNetAmt'     => round($totalNetAmt_, 2),
			'totalOrderMrp'   => (float)$totalOrderMrp_,
			'totalSp'         => (float)$totalAmount_,
			'offDis'          => (float)($totalOrderMrp_ - $totalAmount_),
			'unipointDis'     => (float)$totalDiscount_,
			'totalDiscount'   => (float)($totalDiscount_ + ($totalOrderMrp_ - $totalAmount_)),
		);

		return $record;
	}
}
