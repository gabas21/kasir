<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\CategoryManager;
use Illuminate\Support\Facades\Route;

// Halaman awal: Unified Login (Kasir + Admin dalam 1 halaman)
Route::get('/', \App\Livewire\PosLogin::class)->name('home')->middleware('guest');
Route::get('/pos/login', \App\Livewire\PosLogin::class)->name('pos.login')->middleware('guest');
Route::get('/pos', \App\Livewire\Kasir::class)->name('pos.index')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::middleware('role.not_kasir')->group(function () {
        // Dashboard Admin
        Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

        // Route Dashboard / Admin
        Route::get('/categories', CategoryManager::class)->name('categories.index');
        Route::get('/products', \App\Livewire\ProductManager::class)->name('products.index');
        Route::get('/reports', \App\Livewire\SalesReport::class)->name('reports.index');
        Route::get('/owner', \App\Livewire\OwnerDashboard::class)->name('owner.dashboard');

        // Export
        Route::get('/export/pdf', [\App\Http\Controllers\ExportController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/export/csv', [\App\Http\Controllers\ExportController::class, 'exportCsv'])->name('export.csv');

        // User Management
        Route::get('/users', \App\Livewire\UserManager::class)->name('users.index');
        Route::get('/promos', \App\Livewire\PromoManager::class)->name('promos.index');
    });

    // Profile tetap bisa diakses oleh semua role yang sudah login
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
