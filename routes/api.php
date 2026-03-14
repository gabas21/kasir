<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Android POS Client
|--------------------------------------------------------------------------
| Semua endpoint di sini diakses oleh Aplikasi Android POS.
| Prefix otomatis: /api
| Format respons: JSON standar {status, message, data}
*/

// ── Public (Tanpa Token) ──────────────────────────────────────────────
Route::post('/login', [AuthController::class, 'login']);

// ── Protected (Butuh Token Sanctum) ──────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // Katalog
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    // Transaksi
    Route::post('/checkout', [CheckoutController::class, 'store']);
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show']);

    // Promo
    Route::get('/promos/validate/{code}', [PromoController::class, 'validateCode']);
});
