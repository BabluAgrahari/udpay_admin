<?php

use App\Http\Controllers\CRM\AccountSettingController;
use App\Http\Controllers\Crm\CategoryController;
use App\Http\Controllers\CRM\DashboardController;
use App\Http\Controllers\CRM\LoginController;
use App\Http\Controllers\CRM\ProfileController;
use App\Http\Controllers\CRM\SettingController;
use App\Http\Controllers\CRM\UserController;
use App\Http\Controllers\CRM\ProductController;
use App\Http\Controllers\CRM\BrandController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
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

    Route::resource('user', UserController::class)->middleware('can:isSuperAdmin');
    Route::resource('profile', ProfileController::class)->middleware('can:isMerchant');
    Route::post('save-business', [ProfileController::class, 'saveBusiness'])->middleware('can:isMerchant');
    Route::post('save-cp', [ProfileController::class, 'saveContactPerson'])->middleware('can:isMerchant');
    Route::post('save-bank', [ProfileController::class, 'saveBank'])->middleware('can:isMerchant');
    Route::post('save-kyc', [ProfileController::class, 'saveKyc'])->middleware('can:isMerchant');


    Route::get('/webhook-key', [AccountSettingController::class, 'index']);
    Route::get('/api', [AccountSettingController::class, 'api']);
    Route::post('save-webhook', [AccountSettingController::class, 'saveWebhook']);
    Route::post('reset-key', [AccountSettingController::class, 'resetKey']);

    Route::resource('setting', SettingController::class);
    Route::get('reset-password', [SettingController::class, 'resetPassword']);
    Route::post('reset-password-save', [SettingController::class, 'saveResetPassword']);

    Route::get('logout', [LoginController::class, 'logout']);

    Route::resource('categories', CategoryController::class);
    Route::post('categories/update-status', [CategoryController::class, 'updateStatus']);
    Route::post('categories/update-short', [CategoryController::class, 'updateShortCode']);

    Route::resource('products', ProductController::class);
    Route::post('products/update-status', [ProductController::class, 'updateStatus']);

    // Brand Routes
    Route::resource('brands', BrandController::class);
    Route::post('brands/update-status', [BrandController::class, 'updateStatus']);
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
