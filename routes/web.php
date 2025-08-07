<?php

use App\Http\Controllers\Website\Distributor\DashboardController;
use App\Http\Controllers\Website\Distributor\KYCController;
use App\Http\Controllers\Website\Distributor\AddMoneyController;
use App\Http\Controllers\Website\Distributor\MoneyTransferController;
use App\Http\Controllers\Website\Distributor\WalletController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


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
