<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DeliveryController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application.
|
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Product Routes
    Route::resource('products', ProductController::class);

    // Category Routes
    Route::resource('categories', CategoryController::class);

    // Discount Routes
    Route::resource('discounts', DiscountController::class);

    // Discount Category Routes
    Route::get('discount-categories', [DiscountController::class, 'categoryIndex'])->name('discount-categories.index');
    Route::get('discount-categories/create', [DiscountController::class, 'categoryCreate'])->name('discount-categories.create');
    Route::post('discount-categories', [DiscountController::class, 'categoryStore'])->name('discount-categories.store');
    Route::get('discount-categories/{category}/edit', [DiscountController::class, 'categoryEdit'])->name('discount-categories.edit');
    Route::put('discount-categories/{category}', [DiscountController::class, 'categoryUpdate'])->name('discount-categories.update');
    Route::delete('discount-categories/{category}', [DiscountController::class, 'categoryDestroy'])->name('discount-categories.destroy');

    // Order Routes
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

    // Delivery Routes
    Route::get('/deliveries', [DeliveryController::class, 'index'])->name('deliveries.index');
    Route::get('/deliveries', [DeliveryController::class, 'index'])->name('deliveries.index');
    Route::get('/deliveries/{order}/edit', [DeliveryController::class, 'edit'])->name('deliveries.edit');
    Route::put('/deliveries/{order}', [DeliveryController::class, 'update'])->name('deliveries.update');
});
