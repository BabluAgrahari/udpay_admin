<?php

use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\FrontController;
use App\Http\Controllers\Website\ProductDetailController;
use App\Http\Controllers\Website\CartController;
use App\Http\Controllers\Website\CheckoutController;
use App\Http\Controllers\Website\StaticPageController;
use App\Http\Controllers\Website\AuthController;
use App\Http\Controllers\Website\AddressController;
use App\Http\Controllers\Website\WishlistController;
use App\Http\Controllers\Website\OrderHistoryController;
use App\Http\Controllers\Website\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{category?}', [FrontController::class, 'productList']);
Route::get('/detail/{slug}', [ProductDetailController::class, 'index']);

// Cart Routes
Route::get('/cart', [CartController::class, 'cartList']);
Route::post('/add-to-cart', [CartController::class, 'addToCart']);
Route::post('/update-cart-quantity', [CartController::class, 'updateQuantity']);
Route::post('/remove-from-cart', [CartController::class, 'removeFromCart']);
Route::post('/clear-cart', [CartController::class, 'clearCart']);
Route::get('/cart-summary', [CartController::class, 'getCartSummary']);
Route::post('/apply-coupon', [CartController::class, 'applyCoupon']);
Route::post('/remove-coupon', [CartController::class, 'removeCoupon']);
Route::get('/available-coupons', [CartController::class, 'getAvailableCoupons']);


Route::group(['middleware' => ['customer.auth']], function () {
// Wishlist Routes
Route::post('/wishlist/add', [WishlistController::class, 'addToWishlist']);
Route::get('/wishlist/remove/{id}', [WishlistController::class, 'removeWishlist']);

// Address Routes
Route::prefix('address')->group(function () {
    Route::post('/', [AddressController::class, 'store']);
    Route::put('/{id}', [AddressController::class, 'update']);
    Route::post('/{id}/set-default', [AddressController::class, 'setDefault']);
});

Route::post('/checkout', [CheckoutController::class, 'checkout']);
Route::get('/checkout', [CheckoutController::class, 'index']);
Route::get('buy/{slug}', [CheckoutController::class, 'buyProduct']);
Route::post('/payment-gatway', [CheckoutController::class, 'paymentGatway']);
Route::get('/payment-process', [CheckoutController::class, 'paymentProcess']);
// Route::get('/order-history', [OrderHistoryController::class, 'index']);
Route::get('/order-detail/{id}', [OrderHistoryController::class, 'orderDetail']);


Route::get('/my-account', [DashboardController::class, 'myAccount']);
Route::post('/save-profile', [DashboardController::class, 'saveProfile']);
Route::get('/order-history', [DashboardController::class, 'orderHistory']);
Route::get('/address-book', [DashboardController::class, 'addressBook']);
Route::get('/wishlist', [DashboardController::class, 'wishlist']);
Route::get('/logout', [DashboardController::class, 'logout']);
});



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
Route::get('/download', [StaticPageController::class, 'download'])->name('download');

// Auth Routes
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/check-auth', [AuthController::class, 'checkAuth']);

Route::get('/sitemap.xml', function () {
    return response()->view('sitemap')->header('Content-Type', 'text/xml');
});

// RSS Feed
Route::get('/rss', function () {
    return response()->view('rss')->header('Content-Type', 'application/rss+xml');
});
