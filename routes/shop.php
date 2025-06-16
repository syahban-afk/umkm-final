<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

Route::get('/', [ShopController::class, 'index'])->name('shop.index');
Route::get('/produk/{product}', [ShopController::class, 'show'])->name('shop.show');

Route::middleware(['auth', 'customer'])->group(function () {
    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout routes
    Route::post('/checkout/process', [CartController::class, 'checkout'])->name('checkout.process');
    Route::get('/checkout/success/{order}', [CartController::class, 'checkoutSuccess'])->name('checkout.success');

    // Order routes
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});
