<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Show the checkout page (GET request)
     */
    public function showCheckoutForm(Request $request)
    {
        $user = Auth::user();
        $addresses = $user ? $user->userAddresses()->get() : collect();
        $cartItems = $user ? Cart::with('product')->where('user_id', $user->id)->get() : collect();

        // Calculate cart summary
        $subtotal = $cartItems->sum(function($item) {
            return $item->product ? $item->product->product_sale_price * $item->quantity : 0;
        });
        $total_mrp = $cartItems->sum(function($item) {
            return $item->product && isset($item->product->mrp) ? $item->product->mrp * $item->quantity : 0;
        });
        $total_saving = $total_mrp - $subtotal;
        $total_items = $cartItems->sum('quantity');

        // Handle address creation
        if ($request->isMethod('post') && $request->has('user_add_name')) {
            $validated = $request->validate([
                'user_add_name' => 'required|string|max:255',
                'user_add_mobile' => 'required|string|max:20',
                'user_add_1' => 'required|string|max:255',
                'user_zip_code' => 'required|string|max:10',
                'user_state' => 'required|string|max:100',
                'user_city' => 'required|string|max:100',
                'address_type' => 'required|string',
            ]);
            $validated['user_id'] = $user->id;
            UserAddress::create($validated);
            return redirect()->route('checkout.form')->with('success', 'Address added successfully');
        }

        return view('Website.checkout', compact('addresses', 'cartItems', 'subtotal', 'total_mrp', 'total_saving', 'total_items'));
    }

    /**
     * Handle the checkout process: create order from cart, save address, clear cart.
     */
    public function checkout(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return back()->withErrors(['msg' => 'User not authenticated']);
        }

        // Validate request (address, payment, etc.)
        $validated = $request->validate([
            'address_id' => 'required|exists:user_addresses,id',
            'payment_method' => 'required|in:cod,online',
        ]);

        // Get cart items
        $cartItems = Cart::where('user_id', $user->id)->get();
        if ($cartItems->isEmpty()) {
            return back()->withErrors(['msg' => 'Cart is empty']);
        }

        // Get address
        $address = UserAddress::find($validated['address_id']);

        // Calculate totals (placeholder logic)
        $totalAmount = $cartItems->sum(function($item) {
            return $item->product->product_sale_price * $item->quantity;
        });
        $finalAmount = $totalAmount; // Add discounts, taxes, etc. as needed

        // Create order
        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_number' => uniqid('ORD-'),
                'order_date' => now(),
                'total_amount' => $totalAmount,
                'final_amount' => $finalAmount,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'payment_method' => $request->input('payment_method', 'cod'),
                'delivery_address' => $address->toJson(),
                'products' => $cartItems->toJson(),
                'status' => 1,
            ]);

            // Clear cart
            Cart::where('user_id', $user->id)->delete();

            DB::commit();
            return redirect()->route('checkout.form')->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Order placement failed: ' . $e->getMessage()]);
        }
    }
} 