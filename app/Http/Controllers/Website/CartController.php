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
    /**
     * Add product to cart
     */
    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
        $cookieId = $request->cookie('cart_cookie_id') ?? Str::random(40);
        $userId = auth()->id();
        $cartIdentifier = $userId ? ['user_id' => $userId] : ['cart_cookie_id' => $cookieId];

        // Get product (already validated in CartRequest)
        $product = Product::find($productId);

        // Set the cookie if it doesn't exist
        if (!$request->hasCookie('cart_cookie_id')) {
            Cookie::queue('cart_cookie_id', $cookieId, 60 * 24 * 30); // 30 days
        }

        // Check if product already exists in cart
        if ($userId) {
            $existingCart = Cart::where('user_id', $userId)->where('product_id', $productId)->first();
            if ($existingCart) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Product already in cart',
                    'total_cart_count' => total_cart_count()
                ]);
            }
        } else {
            $existingCart = Cart::where('cart_cookie_id', $cookieId)->where('product_id', $productId)->first();
            if ($existingCart) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Product already in cart',
                    'total_cart_count' => total_cart_count()
                ]);
            }
        }

        // Add to cart
        Cart::create(array_merge($cartIdentifier, [
            'product_id' => $productId,
            'quantity' => $quantity
        ]));

        return response()->json([
            'status' => true,
            'msg' => 'Product added to cart successfully',
            'total_cart_count' => total_cart_count()
        ]);
    }

    /**
     * Display cart list
     */
    public function cartList(Request $request)
    {
        if (Auth::user()) {
            $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        } else {
            $cookieId = $request->cookie('cart_cookie_id');
            if ($cookieId) {
                $cartItems = Cart::with('product')->where('cart_cookie_id', $cookieId)->get();
            } else {
                $cartItems = collect();
            }
        }

        $cart_view = 'Website.cart';
        if ($cartItems->isEmpty()) {
            $cart_view = 'Website.empty-cart';
        }

        return view($cart_view, compact('cartItems'));
    }

    /**
     * Update cart item quantity
     */
    public function updateQuantity(CartRequest $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
        $userId = auth()->id();
        $cookieId = $request->cookie('cart_cookie_id');

        if ($quantity <= 0) {
            return $this->removeFromCart($request);
        }

        // Get product (already validated in CartRequest)
        $product = Product::find($productId);

        $cartIdentifier = $userId ? ['user_id' => $userId] : ['cart_cookie_id' => $cookieId];

        $cartItem = Cart::where(array_merge($cartIdentifier, ['product_id' => $productId]))->first();

        if (!$cartItem) {
            return response()->json(['status' => false, 'msg' => 'Cart item not found']);
        }

        $cartItem->update(['quantity' => $quantity]);

        $cartData = $this->getCartData($request);
        
        return response()->json([
            'status' => true,
            'msg' => 'Quantity updated successfully',
            'cart_data' => $cartData
        ]);
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(CartRequest $request)
    {

        $productId = $request->input('product_id');
        $userId = auth()->id();
        $cookieId = $request->cookie('cart_cookie_id');

        $cartIdentifier = $userId ? ['user_id' => $userId] : ['cart_cookie_id' => $cookieId];

        $cartItem = Cart::where(array_merge($cartIdentifier, ['product_id' => $productId]))->first();

        if (!$cartItem) {
            return response()->json(['status' => false, 'msg' => 'Cart item not found']);
        }

        $cartItem->delete();

        $cartData = $this->getCartData($request);

        return response()->json([
            'status' => true,
            'msg' => 'Item removed from cart successfully',
            'cart_data' => $cartData
        ]);
    }

    /**
     * Clear entire cart
     */
    public function clearCart(Request $request)
    {
        $userId = auth()->id();
        $cookieId = $request->cookie('cart_cookie_id');

        $cartIdentifier = $userId ? ['user_id' => $userId] : ['cart_cookie_id' => $cookieId];

        Cart::where($cartIdentifier)->delete();

        return response()->json([
            'status' => true,
            'msg' => 'Cart cleared successfully',
            'cart_data' => [
                'total_items' => 0,
                'subtotal' => 0,
                'total_saving' => 0,
                'total_mrp' => 0
            ]
        ]);
    }

    /**
     * Get cart data for calculations
     */
    private function getCartData(Request $request)
    {
        if (Auth::user()) {
            $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        } else {
            $cookieId = $request->cookie('cart_cookie_id');
            if ($cookieId) {
                $cartItems = Cart::with('product')->where('cart_cookie_id', $cookieId)->get();
            } else {
                $cartItems = collect();
            }
        }

        $subtotal = 0;
        $total_saving = 0;
        $total_mrp = 0;
        $total_items = 0;

        foreach ($cartItems as $item) {
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

    /**
     * Get cart summary for AJAX requests
     */
    public function getCartSummary(Request $request)
    {
        $cartData = $this->getCartData($request);
        
        return response()->json([
            'status' => true,
            'cart_data' => $cartData
        ]);
    }
} 