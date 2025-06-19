<?php

use App\Http\Controllers\Api\PaymentGatwayController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VcollectController;
use App\Http\Controllers\Api\VirtualAccountController;
use App\Http\Controllers\CRM\CheckoutController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/




// Route::get('index', [AuthController::class, 'index']);
Route::post('auth', [AuthController::class, 'login']);

