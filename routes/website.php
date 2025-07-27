<?php

use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\FrontController;
use App\Http\Controllers\Website\ProductDetailController;
use Illuminate\Support\Facades\Route;



Route::get('/', [HomeController::class, 'index']);
Route::get('/product/{category?}', [FrontController::class, 'productList']);
Route::get('/detail/{slug}', [ProductDetailController::class, 'index']);


Route::get('/sitemap.xml', function () {
    return response()->view('sitemap')->header('Content-Type', 'text/xml');
});

// RSS Feed
Route::get('/rss', function () {
    return response()->view('rss')->header('Content-Type', 'application/rss+xml');
});
