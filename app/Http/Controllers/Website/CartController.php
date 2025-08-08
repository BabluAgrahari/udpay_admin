<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CartRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Coupon;
use Exception;

class CartController extends Controller
{

    public function addToCart(Request $request)
    {
        try {
            $cookieId = $request->cookie('cart_cookie_id') ?? Str::random(40);
            $userId = Auth::user()?->id ?? '';

            $cartIdentifier = $userId ? ['user_id' => $userId] : ['cart_cookie_id' => $cookieId];
            if (!$request->hasCookie('cart_cookie_id')) {
                Cookie::queue('cart_cookie_id', $cookieId, 60 * 24 * 30); // 30 days
            }

            $existingCart = Cart::where(array_merge($cartIdentifier, ['product_id' => $request->product_id]))->first();
            if ($existingCart) {
                return $this->failMsg('Product already in cart');
            }

            $save = new Cart();
            if ($userId) {
                $save->user_id = $userId;
            }
            $save->cart_cookie_id = $cookieId;
            $save->product_id = $request->product_id;
            $save->quantity = 1;
            $save->variant_id = $request->variant_id ?? null;
            $save->attribute_id = $request->attribute_id ?? null;
            if ($save->save()) {
                return $this->successMsg('Product added to cart successfully', ['cartCount' => cartCount()]);
            } else {
                return $this->failMsg('Product not added to cart');
            }
        } catch (\Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }


    public function cartList(Request $request)
    {
        try {
            $query =  Cart::with('product');

            if (Auth::user()) {
                $query->where('user_id', Auth::user()->id);
            } else {
                $query->where('cart_cookie_id', $request->cookie('cart_cookie_id'));
            }
            $cartItems = $query->get();

            $cart_view = 'Website.cart';
            if ($cartItems->isEmpty()) {
                $cart_view = 'Website.empty-cart';
            }
            return view($cart_view, compact('cartItems'));
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }


    public function updateQuantity(CartRequest $request)
    {
        try {
            $productId = $request->product_id;
            $quantity = $request->quantity;
            $userId = Auth::user()?->id ?? '';
            $cookieId = $request->cookie('cart_cookie_id');

            if ($quantity <= 0) {
                return $this->removeFromCart($request);
            }

            $cartIdentifier = $userId ? ['user_id' => $userId] : ['cart_cookie_id' => $cookieId];
            $cartItem = Cart::where(array_merge($cartIdentifier, ['product_id' => $productId]))->first();
            if (!$cartItem) {
                return $this->failMsg('Cart item not found');
            }

            $cartItem->quantity = $quantity;
            if ($cartItem->save()) {
                return $this->successMsg('Quantity updated successfully', ['cart_data' => $this->getCartData($request)]);
            }
            return $this->failMsg('Quantity not updated');
        } catch (\Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }


    public function removeFromCart(CartRequest $request)
    {
        try {
            $productId = $request->input('product_id');
            $userId = Auth::user()?->id ?? '';
            $cookieId = $request->cookie('cart_cookie_id');

            $cartIdentifier = $userId ? ['user_id' => $userId] : ['cart_cookie_id' => $cookieId];
            $cartItem = Cart::where(array_merge($cartIdentifier, ['product_id' => $productId]))->first();

            if (!$cartItem) {
                return $this->failMsg('Cart item not found');
            }

            $cartItem->delete();

            $cartData = $this->getCartData($request);

            return $this->successMsg('Item removed from cart successfully', ['cart_data' => $cartData]);
        } catch (\Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function clearCart(Request $request)
    {
        $userId = Auth::user()?->id ?? '';
        $cookieId = $request->cookie('cart_cookie_id');

        $cartIdentifier = $userId ? ['user_id' => $userId] : ['cart_cookie_id' => $cookieId];

        Cart::where($cartIdentifier)->delete();

        return $this->successMsg('Cart cleared successfully', ['cart_data' => $this->getCartData($request)]);
    }


    private function getCartData(Request $request)
    {
        $query = Cart::with('product');
        if (Auth::user()) {
            $query->where('user_id', Auth::user()->id);
        } else {
            $query->where('cart_cookie_id', $request->cookie('cart_cookie_id'));
        }

        $subtotal = 0;
        $total_saving = 0;
        $total_mrp = 0;
        $total_items = 0;

        foreach ($query->get() as $item) {
            if ($item->product) {
                $itemTotal = $item->product->product_sale_price * $item->quantity;
                $itemMrp = (isset($item->product->mrp) && $item->product->mrp > 0) ? $item->product->mrp * $item->quantity : $itemTotal;

                $subtotal += $itemTotal;
                $total_mrp += $itemMrp;
                $total_items += $item->quantity;

                if (isset($item->product->mrp) && $item->product->mrp > 0) {
                    $total_saving += ($item->product->mrp - $item->product->product_sale_price) * $item->quantity;
                }
            }
        }

        // Calculate coupon discount if applied
        $couponDiscount = 0;
        $finalAmount = $subtotal;
        
        if (session('applied_coupon')) {
            $couponDiscount = session('applied_coupon.discount_amount');
            $finalAmount = $subtotal - $couponDiscount;
        }

        return [
            'total_items' => $total_items,
            'subtotal' => $subtotal,
            'total_saving' => $total_saving,
            'total_mrp' => $total_mrp,
            'coupon_discount' => $couponDiscount,
            'final_amount' => $finalAmount,
            'applied_coupon' => session('applied_coupon')
        ];
    }


    public function getCartSummary(Request $request)
    {
        try {
            $cartData = $this->getCartData($request);

            return $this->successMsg('Cart summary fetched successfully', ['cart_data' => $cartData]);
        } catch (\Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function applyCoupon(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'coupon_code' => 'required|string|max:50'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'msg' => $validator->errors()->first()
                ]);
            }

            $couponCode = strtoupper(trim($request->coupon_code));
            $coupon = Coupon::where('code', $couponCode)->first();

            if (!$coupon) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Invalid coupon code'
                ]);
            }

            // Check if coupon is active
            if (!$coupon->status) {
                return response()->json([
                    'status' => false,
                    'msg' => 'This coupon is inactive'
                ]);
            }

            // Check if coupon is expired
            if ($coupon->isExpired()) {
                return response()->json([
                    'status' => false,
                    'msg' => 'This coupon has expired'
                ]);
            }

            // Check if coupon has started
            if ($coupon->isNotStarted()) {
                return response()->json([
                    'status' => false,
                    'msg' => 'This coupon is not yet active'
                ]);
            }

            // Check if usage limit reached
            if ($coupon->isUsageLimitReached()) {
                return response()->json([
                    'status' => false,
                    'msg' => 'This coupon usage limit has been reached'
                ]);
            }

            // Get cart total from database
            $query = Cart::with('product');
            if (Auth::user()) {
                $query->where('user_id', Auth::user()->id);
            } else {
                $query->where('cart_cookie_id', $request->cookie('cart_cookie_id'));
            }
            $cartItems = $query->get();
            
            $subtotal = 0;
            $total_mrp = 0;

            foreach ($cartItems as $item) {
                $subtotal += ($item->product->product_sale_price * $item->quantity);
                $total_mrp += (isset($item->product->mrp) && $item->product->mrp > 0 ? $item->product->mrp : $item->product->product_sale_price) * $item->quantity;
            }

            // Check minimum amount requirement
            if ($coupon->minimum_amount > 0 && $subtotal < $coupon->minimum_amount) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Minimum order amount of ₹' . $coupon->minimum_amount . ' required for this coupon'
                ]);
            }

            // Calculate discount
            $discount = 0;
            if ($coupon->discount_type === 'percentage') {
                $discount = ($subtotal * $coupon->discount_value) / 100;
                // Apply maximum discount limit
                if ($coupon->maximum_discount > 0 && $discount > $coupon->maximum_discount) {
                    $discount = $coupon->maximum_discount;
                }
            } else {
                $discount = $coupon->discount_value;
            }

            $finalAmount = $subtotal - $discount;

            // Store coupon in session
            session([
                'applied_coupon' => [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'name' => $coupon->name,
                    'discount_type' => $coupon->discount_type,
                    'discount_value' => $coupon->discount_value,
                    'discount_amount' => $discount,
                    'minimum_amount' => $coupon->minimum_amount,
                    'maximum_discount' => $coupon->maximum_discount
                ]
            ]);

            return response()->json([
                'status' => true,
                'msg' => 'Coupon applied successfully!',
                'data' => [
                    'coupon_code' => $coupon->code,
                    'coupon_name' => $coupon->name,
                    'discount_type' => $coupon->discount_type,
                    'discount_value' => $coupon->discount_value,
                    'discount_amount' => $discount,
                    'subtotal' => $subtotal,
                    'final_amount' => $finalAmount,
                    'savings' => $discount
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => 'Something went wrong!'
            ]);
        }
    }

    public function removeCoupon()
    {
        try {
            session()->forget('applied_coupon');
            
            return response()->json([
                'status' => true,
                'msg' => 'Coupon removed successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => 'Something went wrong!'
            ]);
        }
    }

    public function getAvailableCoupons()
    {
        try {
            $coupons = Coupon::active()->get();
            $html = '';
            
            if ($coupons->count() > 0) {
                foreach ($coupons as $coupon) {
                    $html .= '<div class="coupon-item mb-3 p-3 border rounded">';
                    $html .= '<div class="d-flex justify-content-between align-items-start">';
                    $html .= '<div>';
                    $html .= '<h6 class="mb-1">' . $coupon->name . '</h6>';
                    $html .= '<p class="mb-1 text-muted small">' . $coupon->description . '</p>';
                    
                    if ($coupon->discount_type === 'percentage') {
                        $html .= '<p class="mb-1"><strong>' . $coupon->discount_value . '% OFF</strong>';
                        if ($coupon->maximum_discount > 0) {
                            $html .= ' (Max ₹' . $coupon->maximum_discount . ')';
                        }
                        $html .= '</p>';
                    } else {
                        $html .= '<p class="mb-1"><strong>₹' . $coupon->discount_value . ' OFF</strong></p>';
                    }
                    
                    if ($coupon->minimum_amount > 0) {
                        $html .= '<p class="mb-1 text-muted small">Min. order: ₹' . $coupon->minimum_amount . '</p>';
                    }
                    
                    if ($coupon->usage_limit) {
                        $html .= '<p class="mb-1 text-muted small">Usage: ' . $coupon->used_count . '/' . $coupon->usage_limit . '</p>';
                    }
                    
                    $html .= '</div>';
                    $html .= '<div class="text-end">';
                    $html .= '<button type="button" class="btn btn-sm btn-outline-primary" onclick="applyCouponCode(\'' . $coupon->code . '\')">Apply</button>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</div>';
                }
            } else {
                $html = '<p class="text-muted">No coupons available at the moment.</p>';
            }
            
            return response()->json([
                'status' => true,
                'html' => $html
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => 'Something went wrong!'
            ]);
        }
    }
}
