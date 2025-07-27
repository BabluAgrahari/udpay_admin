<?php

use App\Http\Controllers\CRM\AccountSettingController;
use App\Http\Controllers\CRM\BrandController;
use App\Http\Controllers\CRM\CategoryController;
use App\Http\Controllers\CRM\DashboardController;
use App\Http\Controllers\CRM\LoginController;
use App\Http\Controllers\CRM\OrderController;
use App\Http\Controllers\CRM\PanelUserController;
use App\Http\Controllers\CRM\ProductController;
use App\Http\Controllers\CRM\ProfileController;
use App\Http\Controllers\CRM\SettingController;
use App\Http\Controllers\CRM\StockController;
use App\Http\Controllers\CRM\StockHistoryController;
use App\Http\Controllers\CRM\UnitController;
use App\Http\Controllers\CRM\UserController;
use App\Http\Controllers\CRM\WalletManagementController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register web routes for your application. These
 * | routes are loaded by the RouteServiceProvider and all of them will
 * | be assigned to the "web" middleware group. Make something great!
 * |
 */

Route::group(['middleware' => 'guest'], function () {
    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    Route::get('register', [LoginController::class, 'register']);
    Route::post('register', [LoginController::class, 'saveRegister']);

    Route::get('/', function () {
        // return view('welcome');
        return redirect('login');
    });
});

Route::group(['prefix' => 'crm', 'middleware' => 'auth'], function () {
    Route::get('dashboard', [DashboardController::class, 'index']);

    Route::resource('user', UserController::class);

    // KYC Management Routes
    Route::get('user/{id}/kyc', [UserController::class, 'kyc']);
    Route::post('user/{id}/kyc', [UserController::class, 'storeKyc']);
    Route::post('user/{id}/kyc-status', [UserController::class, 'updateKycStatus']);

    // Panel User Routes
    Route::resource('panel-users', PanelUserController::class)->middleware('can:isSuperAdmin');
    Route::get('panel-users/{id}/change-password', [PanelUserController::class, 'changePassword'])->middleware('can:isSuperAdmin');
    Route::post('panel-users/{id}/change-password', [PanelUserController::class, 'updatePassword'])->middleware('can:isSuperAdmin');
    Route::post('panel-users/update-status', [PanelUserController::class, 'updateStatus'])->middleware('can:isSuperAdmin');

    Route::resource('profile', ProfileController::class);
    // Route::post('save-business', [ProfileController::class, 'saveBusiness'])->middleware('can:isMerchant');
    // Route::post('save-cp', [ProfileController::class, 'saveContactPerson'])->middleware('can:isMerchant');
    // Route::post('save-bank', [ProfileController::class, 'saveBank'])->middleware('can:isMerchant');
    // Route::post('save-kyc', [ProfileController::class, 'saveKyc'])->middleware('can:isMerchant');

    // Route::get('/webhook-key', [AccountSettingController::class, 'index']);
    // Route::get('/api', [AccountSettingController::class, 'api']);
    // Route::post('save-webhook', [AccountSettingController::class, 'saveWebhook']);
    // Route::post('reset-key', [AccountSettingController::class, 'resetKey']);

    Route::resource('setting', SettingController::class);
    Route::get('reset-password', [SettingController::class, 'resetPassword']);
    Route::post('reset-password-save', [SettingController::class, 'saveResetPassword']);

    Route::get('logout', [LoginController::class, 'logout']);

    Route::get('categories/datatable-list', [CategoryController::class, 'datatable']);
    Route::resource('categories', CategoryController::class);
    Route::post('categories/update-status', [CategoryController::class, 'updateStatus']);
    Route::post('categories/update-short', [CategoryController::class, 'updateShortCode']);
    

    Route::resource('products', ProductController::class);
    Route::post('products/update-status', [ProductController::class, 'updateStatus']);

    // Brand Routes
    Route::resource('brands', BrandController::class);
    Route::post('brands/update-status', [BrandController::class, 'updateStatus']);

    // Unit Routes
    Route::resource('units', UnitController::class);
    Route::post('units/update-status', [UnitController::class, 'updateStatus']);

    // Order Routes
    Route::get('order', [OrderController::class, 'index']);

    // Stock History Routes
    Route::get('stock-history', [StockHistoryController::class, 'index']);
    Route::get('stock-history/create', [StockHistoryController::class, 'create'])->name('stock-history.create');
    Route::post('stock-history', [StockHistoryController::class, 'store'])->name('stock-history.store');

    // Wallet Management
    Route::prefix('wallet')->group(function () {
        Route::get('/transfer', [WalletManagementController::class, 'index'])->middleware('can:isSuperAdmin');
        Route::post('/transfer', [WalletManagementController::class, 'store'])->middleware('can:isSuperAdmin');
        Route::get('/get-user-wallet', [WalletManagementController::class, 'getUserWallet']);
        Route::get('/history', [WalletManagementController::class, 'history']);
        Route::get('/transaction-details', [WalletManagementController::class, 'getTransactionDetails']);
        
        // User Transfer Routes
        Route::get('/user-transfer', [WalletManagementController::class, 'userTransferIndex'])->middleware('can:isAdmin');
        Route::post('/user-transfer', [WalletManagementController::class, 'userTransfer'])->middleware('can:isAdmin');
        Route::get('/get-user-details', [WalletManagementController::class, 'getUserDetails']);
        Route::get('/get-admin-wallet', [WalletManagementController::class, 'getAdminWallet']);
        
        // User to User Transfer Routes
        Route::get('/user-to-user-transfer', [WalletManagementController::class, 'userToUserTransferIndex'])->middleware('can:isAdmin');
        Route::post('/user-to-user-transfer', [WalletManagementController::class, 'userToUserTransfer'])->middleware('can:isAdmin');
    });
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

Route::prefix('jobs')->group(function () {
    Route::queueMonitor();
});
