<?php

use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\FrontController;
use App\Http\Controllers\Website\ProductDetailController;
use App\Http\Controllers\Website\CartController;
use App\Http\Controllers\Website\CheckoutController;
use App\Http\Controllers\Website\StaticPageController;
use Illuminate\Support\Facades\Route;



Route::get('/', [HomeController::class, 'index']);
Route::get('/product/{category?}', [FrontController::class, 'productList']);
Route::get('/detail/{slug}', [ProductDetailController::class, 'index']);

// Cart Routes
Route::get('/cart', [CartController::class, 'cartList']);
Route::post('/add-to-cart', [CartController::class, 'addToCart']);
Route::post('/update-cart-quantity', [CartController::class, 'updateQuantity']);
Route::post('/remove-from-cart', [CartController::class, 'removeFromCart']);
Route::post('/clear-cart', [CartController::class, 'clearCart']);
Route::get('/cart-summary', [CartController::class, 'getCartSummary']);
Route::post('/checkout', [CheckoutController::class, 'checkout']);
Route::get('/checkout', [CheckoutController::class, 'index']);

// Static Pages Routes
Route::get('/about-us', [StaticPageController::class, 'about'])->name('about');
Route::get('/contact-us', [StaticPageController::class, 'contact'])->name('contact');
Route::get('/privacy-policy', [StaticPageController::class, 'privacyPolicy'])->name('privacy.policy');
Route::get('/terms-conditions', [StaticPageController::class, 'termsConditions'])->name('terms.conditions');
Route::get('/return-policy', [StaticPageController::class, 'returnPolicy'])->name('return.policy');
Route::get('/shipping-policy', [StaticPageController::class, 'shippingPolicy'])->name('shipping.policy');
Route::get('/legal-documents', [StaticPageController::class, 'legalDocs'])->name('legal.docs');
Route::get('/faq', [StaticPageController::class, 'faq'])->name('faq');
Route::get('/grievance-cell', [StaticPageController::class, 'grievanceCell'])->name('grievance.cell');
Route::get('/track-order', [StaticPageController::class, 'trackOrder'])->name('track.order');
Route::get('/compliance-documents', [StaticPageController::class, 'complianceDocuments'])->name('compliance.documents');

Route::get('/sitemap.xml', function () {
    return response()->view('sitemap')->header('Content-Type', 'text/xml');
});

// RSS Feed
Route::get('/rss', function () {
    return response()->view('rss')->header('Content-Type', 'application/rss+xml');
});
