<?php

use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\SignupController;
use App\Http\Controllers\Website\SigninController;
use Illuminate\Support\Facades\Route;



Route::get('/', [HomeController::class, 'index']);
Route::get('/signup', [SignupController::class, 'index']);

// Signin routes
Route::get('/signin', [SigninController::class, 'index'])->name('signin');
Route::post('/signin', [SigninController::class, 'login']);
Route::get('/logout', [SigninController::class, 'logout']);

// Signup process routes
Route::post('/signup/verify-phone', [SignupController::class, 'verifyPhone']);
Route::post('/signup/verify-otp', [SignupController::class, 'verifyOtp']);
Route::post('/signup/resend-otp', [SignupController::class, 'resendOtp']);
Route::post('/signup/complete', [SignupController::class, 'completeRegistration']);




Route::get('/sitemap.xml', function () {
    return response()->view('sitemap')->header('Content-Type', 'text/xml');
});

// RSS Feed
Route::get('/rss', function () {
    return response()->view('rss')->header('Content-Type', 'application/rss+xml');
}); 