<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::middleware('auth')->group(function () {

    Route::get('/overview', function () {
        return Inertia::render('Dashboard');
    })->name('overview');

    Route::group(['prefix' => 'products', 'as' => 'products.'], function () {

        Route::get('export', [ProductController::class, 'export'])->name('export');

        Route::get('', [ProductController::class, 'index'])->name('index');
        Route::get('create', [ProductController::class, 'create'])->name('create');
        Route::post('', [ProductController::class, 'store'])->name('store');
        Route::get('{product}', [ProductController::class, 'show'])->name('show');
        Route::get('{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('{product}', [ProductController::class, 'update'])->name('update');
        Route::get('{product}/delete', [ProductController::class, 'confirmDelete'])->name('confirm-delete');
        Route::delete('{products}', [ProductController::class, 'destroyMany'])->name('destroyMany');
    });

    Route::group(['prefix' => 'orders', 'as' => 'orders.'], function () {

        Route::get('', [OrderController::class, 'index'])->name('index');
        Route::get('{order}', [OrderController::class, 'show'])->name('show');
        Route::put('{order}/status', [OrderController::class, 'updateStatus']);
    });
});

require __DIR__.'/auth.php';
