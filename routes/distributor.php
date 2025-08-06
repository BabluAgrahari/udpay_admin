<?php

use App\Http\Controllers\Website\Distributor\DashboardController;
use App\Http\Controllers\Website\Distributor\KYCController;
use App\Http\Controllers\Website\Distributor\AddMoneyController;
use App\Http\Controllers\Website\Distributor\MoneyTransferController;
use App\Http\Controllers\Website\Distributor\WalletController;
use App\Http\Controllers\Website\Distributor\SignupController;
use Illuminate\Support\Facades\Route;


Route::get('/distributor/signup', [SignupController::class, 'index']);
Route::post('/distributor/signup/verify-phone', [SignupController::class, 'verifyPhone']);
Route::post('/distributor/signup/verify-otp', [SignupController::class, 'verifyOTP']);
Route::post('/distributor/signup/complete', [SignupController::class, 'completeRegistration']);
Route::post('/distributor/signup/resend-otp', [SignupController::class, 'resendOTP']);


Route::group(['prefix' => 'distributor', 'middleware' => 'distributor.auth'], function () {
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

});
