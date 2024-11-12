<?php

// use App\Http\Controllers\AdminCartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminCartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;

use App\Models\User;

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




Route::delete('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');

Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
Route::post('/cart/update/{productId}', [CartController::class, 'updateItem'])->name('cart.update');
Route::delete('/cart/remove/{productId}', [CartController::class, 'removeItem'])->name('cart.remove');
Route::put('/cart/update/{productId}', [CartController::class, 'updateItem'])->name('cart.update');

Route::get('/hihi', [CartController::class, 'index'])->name('hihi');

//checkout
Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'processCheckout'])->name('checkout.process');

Route::get('/order/success', function () {
    return view('checkout.success');
})->name('order.success');


//mini cart


// admin cart
Route::get('/carts', [AdminCartController::class, 'index'])->name('admin.carts.index');
Route::get('/cart/{id}', [AdminCartController::class, 'show'])->name('admin.cart.show');
Route::delete('/cart/{id}', [AdminCartController::class, 'destroy'])->name('admin.cart.destroy');

// login-logout-register
Route::get('/login',[UserController::class,'login'])->name('login');
Route::post('/login',[UserController::class,'postlogin']);

Route::get('/register',[UserController::class,'register'])->name('register');
Route::post('/register',[UserController::class,'postRegister']);

Route::post('/logout',[UserController::class, 'logout'])->name('logout');
//dashboard 
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
