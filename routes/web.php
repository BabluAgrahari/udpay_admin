<?php

use App\Http\Controllers\Website\Distributor\DashboardController;
use App\Http\Controllers\Website\Distributor\KYCController;
use App\Http\Controllers\Website\Distributor\AddMoneyController;
use App\Http\Controllers\Website\Distributor\MoneyTransferController;
use App\Http\Controllers\Website\Distributor\WalletController;
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

    // Wallet Routes
    Route::get('wallet/add-money', [AddMoneyController::class, 'addMoney']);
    Route::post('wallet/preview-add-money', [AddMoneyController::class, 'previewAddMoney']);
    Route::post('wallet/add-money-save', [AddMoneyController::class, 'addMoneySave']);

    // Money Transfer Routes
    Route::get('wallet/money-transfer', [MoneyTransferController::class, 'index']);
    Route::post('money-transfer/get-user-name', [MoneyTransferController::class, 'getUserName']);
    Route::post('money-transfer/transfer', [MoneyTransferController::class, 'transferMoney']);

    // Wallet Routes
    Route::get('wallet/my-payout', [WalletController::class, 'myPayout']);
    Route::get('wallet/transaction-history', [WalletController::class, 'transactionHistory']);
    Route::get('wallet/wallet-balance', [WalletController::class, 'walletBalance']);
    Route::get('wallet/my-earning', [WalletController::class, 'myEarning']);
    Route::get('wallet/redeem-transaction', [WalletController::class, 'redeemTransaction']);

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
