<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ShopController;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Shop Routes
Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name('index');
    Route::get('/product/{id}', [ShopController::class, 'product'])->name('product');
    Route::get('/cart', [ShopController::class, 'cart'])->name('cart');
    Route::post('/cart', [ShopController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update', [ShopController::class, 'updateCart'])->name('cart.update');
    Route::post('/cart/remove', [ShopController::class, 'removeCart'])->name('cart.remove');
    Route::get('/checkout', [ShopController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [ShopController::class, 'placeOrder'])->name('checkout.store');
    Route::get('/wishlist', [ShopController::class, 'wishlist'])->name('wishlist');
    Route::get('/compare', [ShopController::class, 'compare'])->name('compare');
    Route::get('/account', [CustomerAuthController::class, 'show'])->name('account');
    Route::post('/account/login', [CustomerAuthController::class, 'login'])->name('account.login');
    Route::post('/account/register', [CustomerAuthController::class, 'register'])->name('account.register');
    Route::post('/account/logout', [CustomerAuthController::class, 'logout'])->name('account.logout');
    Route::get('/auth/google', [CustomerAuthController::class, 'redirectToGoogle'])
        ->name('auth.google.redirect');
    Route::get('/auth/google/callback', [CustomerAuthController::class, 'handleGoogleCallback'])
        ->name('auth.google.callback');
    Route::post('/auth/google/one-tap', [CustomerAuthController::class, 'googleOneTap'])
        ->name('auth.google.one-tap');
    Route::middleware('auth:customer')->group(function () {
        Route::get('/account/verify-email', [CustomerAuthController::class, 'verificationNotice'])
            ->name('verification.notice');
        Route::get('/account/verify-email/{id}/{hash}', [CustomerAuthController::class, 'verifyEmail'])
            ->middleware('signed')
            ->name('verification.verify');
        Route::post('/account/verification-notification', [CustomerAuthController::class, 'resendVerification'])
            ->middleware('throttle:6,1')
            ->name('verification.send');
        Route::post('/account/profile', [CustomerAuthController::class, 'updateProfile'])
            ->name('account.profile.update');
        Route::post('/account/addresses', [CustomerAuthController::class, 'storeAddress'])->name('account.addresses.store');
        Route::get('/orders', [CustomerAuthController::class, 'ordersIndex'])->name('orders.index');
        Route::get('/orders/{orderId}', [CustomerAuthController::class, 'orderShow'])->name('orders.show');
    });
    Route::get('/collections', [ShopController::class, 'collections'])->name('collections');
    Route::get('/fullwidth', [ShopController::class, 'fullwidth'])->name('fullwidth');
});

// Pages Routes
Route::prefix('pages')->name('pages.')->group(function () {
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/faq', [PageController::class, 'faq'])->name('faq');
});

// Contact
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

// Blog Routes
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/{id}', [BlogController::class, 'show'])->name('show');
});

Route::get('/login', function () {
    return redirect()->route('shop.account');
})->name('login');
