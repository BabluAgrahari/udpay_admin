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

        return [
            'total_items' => $total_items,
            'subtotal' => $subtotal,
            'total_saving' => $total_saving,
            'total_mrp' => $total_mrp
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
}
