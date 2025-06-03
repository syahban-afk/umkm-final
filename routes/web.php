<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;

Route::get('/', [ShopController::class, 'index'])->name('shop.index');
Route::get('/produk/{product}', [ShopController::class, 'show'])->name('shop.show');
Route::get('/about', function () {
    return view('shop.about');
});

Route::get('/contact', function () {
    return view('shop.contact');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/become-admin', [RoleController::class, 'becomeAdmin'])->name('become.admin');
    Route::post('/wishlist/add/{product}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.delete');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/shop.php';
