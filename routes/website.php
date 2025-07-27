<?php

use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\FrontController;
use App\Http\Controllers\Website\CartController;
use Illuminate\Support\Facades\Route;



Route::get('/', [HomeController::class, 'index']);
Route::get('/product/{category?}', [FrontController::class, 'productList']);
Route::get('/detail/{slug}', [FrontController::class, 'detail']);

// Cart Routes
Route::get('/cart', [CartController::class, 'cartList']);
Route::post('/add-to-cart', [CartController::class, 'addToCart']);
Route::post('/update-cart-quantity', [CartController::class, 'updateQuantity']);
Route::post('/remove-from-cart', [CartController::class, 'removeFromCart']);
Route::post('/clear-cart', [CartController::class, 'clearCart']);
Route::get('/cart-summary', [CartController::class, 'getCartSummary']);
Route::post('/checkout', [\App\Http\Controllers\Website\CheckoutController::class, 'checkout'])->name('checkout');
Route::get('/checkout', [\App\Http\Controllers\Website\CheckoutController::class, 'showCheckoutForm'])->name('checkout.form');


Route::get('/sitemap.xml', function () {
    return response()->view('sitemap')->header('Content-Type', 'text/xml');
});

// RSS Feed
Route::get('/rss', function () {
    return response()->view('rss')->header('Content-Type', 'application/rss+xml');
}); 