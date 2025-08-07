<?php

use App\Http\Controllers\CRM\AccountSettingController;
use App\Http\Controllers\CRM\BrandController;
use App\Http\Controllers\CRM\CategoryController;
use App\Http\Controllers\CRM\CouponController;
use App\Http\Controllers\CRM\DashboardController;
use App\Http\Controllers\CRM\LoginController;
use App\Http\Controllers\CRM\OrderController;
use App\Http\Controllers\CRM\PanelUserController;
use App\Http\Controllers\CRM\ProductController;
use App\Http\Controllers\CRM\ProductReelController;
use App\Http\Controllers\CRM\ProfileController;
use App\Http\Controllers\CRM\SettingController;
use App\Http\Controllers\CRM\StockController;
use App\Http\Controllers\CRM\StockHistoryController;
use App\Http\Controllers\CRM\UnitController;
use App\Http\Controllers\CRM\UserController;
use App\Http\Controllers\CRM\PickupAddressController;
use App\Http\Controllers\CRM\SliderController;
use App\Http\Controllers\CRM\WalletManagementController;
use App\Http\Controllers\CRM\RolePermissionController;
use App\Http\Controllers\CRM\ReviewController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;



Route::group(['middleware' => 'guest'], function () {
    Route::get('crm', [LoginController::class, 'index'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    Route::get('register', [LoginController::class, 'register']);
    Route::post('register', [LoginController::class, 'saveRegister']);

    // Route::get('/crm', function () {
    //     return redirect('login');
    // });
});

// Route::get('dashboard', [DashboardController::class, 'index']);
Route::group(['prefix' => 'crm', 'middleware' => 'crm.auth'], function () {

   Route::get('dashboard', [DashboardController::class, 'index']);

    Route::get('user/datatable-list', [UserController::class, 'datatable'])->middleware('permission:user');
    Route::resource('user', UserController::class)->middleware('permission:user');
    Route::get('user/{id}/kyc', [UserController::class, 'kyc'])->middleware('permission:user');
    Route::post('user/{id}/kyc', [UserController::class, 'storeKyc'])->middleware('permission:user');
    Route::post('user/{id}/kyc-status', [UserController::class, 'updateKycStatus'])->middleware('permission:user');
    

    // Panel User Routes
    Route::get('panel-users/datatable-list', [PanelUserController::class, 'datatable'])->middleware('permission:panel_user');
    Route::resource('panel-users', PanelUserController::class)->middleware('permission:panel_user');
    Route::get('panel-users/{id}/change-password', [PanelUserController::class, 'changePassword'])->middleware('permission:panel_user');
    Route::post('panel-users/{id}/change-password', [PanelUserController::class, 'updatePassword'])->middleware('permission:panel_user');
    Route::post('panel-users/update-status', [PanelUserController::class, 'updateStatus'])->middleware('permission:panel_user');

    Route::get('categories/datatable-list', [CategoryController::class, 'datatable'])->middleware('permission:category');
    Route::resource('categories', CategoryController::class)->middleware('permission:category');
    Route::post('categories/update-status', [CategoryController::class, 'updateStatus'])->middleware('permission:category');
    
    // Product Routes
    Route::get('products/datatable-list', [ProductController::class, 'datatable'])->middleware('permission:product');
    Route::resource('products', ProductController::class)->middleware('permission:product');
    Route::post('products/update-status', [ProductController::class, 'updateStatus'])->middleware('permission:product');
    Route::get('products/{id}/details', [ProductController::class, 'details'])->middleware('permission:product');
    Route::post('products/store-detail', [ProductController::class, 'storeDetail'])->middleware('permission:product');
    Route::get('products/{id}/detail', [ProductController::class, 'getDetail'])->middleware('permission:product');

    // Product Reels Routes
    Route::get('products/{productId}/reels', [ProductReelController::class, 'index'])->middleware('permission:product');
    Route::post('products/{productId}/reels', [ProductReelController::class, 'store'])->middleware('permission:product');
    Route::get('products/{productId}/reels/list', [ProductReelController::class, 'getReels'])->middleware('permission:product');
    Route::post('reels/{reelId}/status', [ProductReelController::class, 'updateStatus'])->middleware('permission:product');
    Route::delete('reels/{reelId}', [ProductReelController::class, 'destroy'])->middleware('permission:product');

    // Brand Routes
    Route::get('brands/datatable-list', [BrandController::class, 'datatable'])->middleware('permission:brand');
    Route::resource('brands', BrandController::class)->middleware('permission:brand');
    Route::post('brands/update-status', [BrandController::class, 'updateStatus'])->middleware('permission:brand');
   
    // Coupon Routes
    Route::get('coupons/datatable-list', [CouponController::class, 'datatable'])->middleware('permission:coupon');
    Route::resource('coupons', CouponController::class)->middleware('permission:coupon');
    Route::post('coupons/update-status', [CouponController::class, 'updateStatus'])->middleware('permission:coupon');
   
    // Unit Routes
    Route::get('units/datatable-list', [UnitController::class, 'datatable'])->middleware('permission:unit');
    Route::resource('units', UnitController::class)->middleware('permission:unit');
    Route::post('units/update-status', [UnitController::class, 'updateStatus'])->middleware('permission:unit');

    // Pickup Address Routes
    Route::get('pickup-addresses/datatable-list', [PickupAddressController::class, 'datatable'])->middleware('permission:pickup_address');
    Route::get('pickup-addresses/get-by-type', [PickupAddressController::class, 'getByType'])->middleware('permission:pickup_address');
    Route::resource('pickup-addresses', PickupAddressController::class)->middleware('permission:pickup_address');
    Route::post('pickup-addresses/update-status', [PickupAddressController::class, 'updateStatus'])->middleware('permission:pickup_address');

    // Order Routes
    Route::get('order', [OrderController::class, 'index'])->middleware('permission:order');
    Route::get('orders/datatable-list', [OrderController::class, 'datatable'])->middleware('permission:order');
    Route::get('orders/ship/{id}', [OrderController::class, 'ship'])->middleware('permission:order');
    Route::post('orders/ship', [OrderController::class, 'saveShipment'])->middleware('permission:order');
    Route::get('orders/invoice/{id}', [OrderController::class, 'downloadInvoice'])->middleware('permission:order');
    Route::post('orders/push-to-courier/{id}', [OrderController::class, 'pushToCourier'])->middleware('permission:order');
    Route::get('orders/insert-test-data', [OrderController::class, 'insertTestData'])->middleware('permission:order');

    // Stock History Routes
    Route::get('stock-history/datatable-list', [StockHistoryController::class, 'datatable'])->middleware('permission:stock_history');
    Route::get('stock-history', [StockHistoryController::class, 'index'])->middleware('permission:stock_history');
    Route::get('stock-history/create', [StockHistoryController::class, 'create'])->name('stock-history.create')->middleware('permission:stock_history');
    Route::post('stock-history', [StockHistoryController::class, 'store'])->name('stock-history.store')->middleware('permission:stock_history');
    

    // Wallet Management
    Route::prefix('wallet')->group(function () {
        Route::get('/transfer', [WalletManagementController::class, 'index'])->middleware('permission:transfer_money_to_admin');
        Route::post('/transfer', [WalletManagementController::class, 'store'])->middleware('permission:transfer_money_to_admin');
        Route::get('/get-admin-wallet', [WalletManagementController::class, 'getAdminWallet'])->middleware('permission:transfer_money_to_admin');

        Route::get('/history', [WalletManagementController::class, 'history'])->middleware('permission:transfer_history');
        Route::get('/transaction-details', [WalletManagementController::class, 'getTransactionDetails'])->middleware('permission:transfer_history');
        
        // User Transfer Routes
        Route::get('/user-transfer', [WalletManagementController::class, 'userTransferIndex'])->middleware('permission:transfer_money_to_user');
        Route::post('/user-transfer', [WalletManagementController::class, 'userTransfer'])->middleware('permission:transfer_money_to_user');
       
        Route::get('/get-user-details', [WalletManagementController::class, 'getUserDetails'])->middleware('permission:transfer_money_to_user | transfer_money_to_user_to_user');
        Route::get('/get-user-wallet', [WalletManagementController::class, 'getUserWallet'])->middleware('permission:transfer_money_to_user | transfer_money_to_user_to_user');

        // User to User Transfer Routes
        Route::get('/user-to-user-transfer', [WalletManagementController::class, 'userToUserTransferIndex'])->middleware('permission:transfer_money_to_user_to_user');
        Route::post('/user-to-user-transfer', [WalletManagementController::class, 'userToUserTransfer'])->middleware('permission:transfer_money_to_user_to_user');
    });

    Route::get('wallet/history/datatable-list', [WalletManagementController::class, 'datatable'])->middleware('permission:transfer_history');

    // Slider Module
    Route::resource('slider',SliderController::class)->middleware('permission:slider');
    Route::post('slider/{slider}/status', [SliderController::class, 'updateStatus'])->middleware('permission:slider');

    // Review Module
    Route::get('reviews', [ReviewController::class, 'index'])->name('crm.reviews.index')->middleware('permission:review');
    Route::get('reviews/datatable-list', [ReviewController::class, 'datatable'])->middleware('permission:review');
    Route::get('reviews/{id}', [ReviewController::class, 'show'])->middleware('permission:review');
    Route::put('reviews/{id}/status', [ReviewController::class, 'updateStatus'])->middleware('permission:review');
    Route::delete('reviews/{id}', [ReviewController::class, 'destroy'])->middleware('permission:review');
    Route::get('reviews-statistics', [ReviewController::class, 'statistics'])->middleware('permission:review');

    Route::resource('role-permission', RolePermissionController::class)->middleware('can:isSuperAdmin');
    Route::post('save-module-permission', [RolePermissionController::class, 'saveModulePermission'])->middleware('can:isSuperAdmin');


    Route::resource('profile', ProfileController::class);
    Route::resource('setting', SettingController::class);
    Route::get('reset-password', [SettingController::class, 'resetPassword']);
    Route::post('reset-password-save', [SettingController::class, 'saveResetPassword']);
    Route::get('logout', [LoginController::class, 'logout']);
});












Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::get('/optimize', function () {
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');
    Artisan::call('config:clear');
    Artisan::call('optimize');
    $output = Artisan::call('env');
    return response()->json(['message' => 'Application optimized successfully.']);
});

Route::get('/dump', function () {
    // Run the composer dump-autoload command
    $output = [];
    $returnVar = 0;

    exec('composer dump-autoload', $output, $returnVar);

    return response()->json([
        'output' => $output,
        'status' => $returnVar,
    ]);
});

// Route::prefix('jobs')->group(function () {
//     Route::queueMonitor();
// });
