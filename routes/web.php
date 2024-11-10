<?php

use App\Http\Controllers\AdminCartController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// client

//home
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

//detail product
Route::get('product/{id}', [\App\Http\Controllers\HomeController::class, 'detailProduct'])->name('detail-product');




//admin
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
Route::post('/cart/update/{productId}', [CartController::class, 'updateItem'])->name('cart.update');
Route::delete('/cart/remove/{productId}', [CartController::class, 'removeItem'])->name('cart.remove');
Route::put('/cart/update/{productId}', [CartController::class, 'updateItem'])->name('cart.update');

Route::get('/hihi', [CartController::class, 'index'])->name('client.cart.hihi');

//checkout
Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'processCheckout'])->name('checkout.process');

Route::get('/order/success', function () {
    return view('checkout.success');
})->name('order.success');


// admin
Route::get('/carts', [AdminCartController::class, 'index'])->name('admin.carts.index');
Route::get('/cart/{id}', [AdminCartController::class, 'show'])->name('admin.cart.show');
Route::delete('/cart/{id}', [AdminCartController::class, 'destroy'])->name('admin.cart.destroy');



// whishlist

Route::get('/wishlist', [WishlistController::class, 'show'])->name('wishlist.show');
