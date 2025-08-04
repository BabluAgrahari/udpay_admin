<?php

use App\Http\Controllers\Website\Distributor\DashboardController;
use App\Http\Controllers\Website\Distributor\KYCController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest'], function () {
    // Route::get('login', [LoginController::class, 'index'])->name('login');
    // Route::post('login', [LoginController::class, 'login']);

    // Route::get('register', [LoginController::class, 'register']);
    // Route::post('register', [LoginController::class, 'saveRegister']);

    // Route::get('/crm', function () {
    //     return redirect('login');
    // });
});

// Route::get('dashboard', [DashboardController::class, 'index']);
Route::group(['prefix' => 'distributor', 'middleware' => 'auth'], function () {
    Route::get('/{type}', [DashboardController::class, 'index']);
    Route::get('/user-level-list/{level}', [DashboardController::class, 'userLeavelList']);
    
    // KYC Routes
    Route::post('kyc/personal-details', [KYCController::class, 'updatePersonalDetails']);
    Route::post('kyc/bank-details', [KYCController::class, 'updateBankDetails']);
    Route::post('kyc/documents', [KYCController::class, 'updateKYCDocuments']);
    Route::get('kyc/status', [KYCController::class, 'getKYCStatus']);
    
    // Route::resource('setting', SettingController::class);
    // Route::get('reset-password', [SettingController::class, 'resetPassword']);
    // Route::post('reset-password-save', [SettingController::class, 'saveResetPassword']);
    // Route::get('logout', [LoginController::class, 'logout']);
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
