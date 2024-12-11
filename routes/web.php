<?php


use App\Models\User;
// use App\Http\Controllers\AdminCartController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsMember;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
// use App\Http\Controllers\AdminCartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AdminCartController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PromotionController;

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

Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');



// Route::delete('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');

// Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
// Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
// Route::post('/cart/update/{productId}', [CartController::class, 'updateItem'])->name('cart.update');
// Route::delete('/cart/remove/{productId}', [CartController::class, 'removeItem'])->name('cart.remove');
// Route::put('/cart/update/{productId}', [CartController::class, 'updateItem'])->name('cart.update');

// Route::get('/hihi', [CartController::class, 'index'])->name('hihi');



// // admin cart
Route::get('/carts', [AdminCartController::class, 'index'])->name('admin.carts.index');
Route::get('/cart/{id}', [AdminCartController::class, 'show'])->name('admin.cart.show');
Route::delete('/cart/{id}', [AdminCartController::class, 'destroy'])->name('admin.cart.destroy');



// // whishlist

Route::get('/wishlist', [WishlistController::class, 'show'])->name('wishlist.show');



// KIỀU DUY DU
// {{

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['auth', IsAdmin::class]);
//detail product
Route::get('/product/{id}', [\App\Http\Controllers\HomeController::class, 'detailProduct'])->name('detail-product');
// Route::get('/create-prd', [DashboardController::class, 'createProduct'])->middleware(['auth',IsAdmin::class]);
// Route::post('/create-prd', [DashboardController::class, 'createProduct'])->middleware(['auth',IsAdmin::class]);

Route::prefix('admin')->name('admin.')->middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::post('products/{product}/update', [ProductController::class, 'update'])->name('products.update');


    //khuyến mãi
    Route::get('promotions', [PromotionController::class, 'index'])->name('promotions.index');
    Route::get('promotions/create', [PromotionController::class, 'create'])->name('promotions.create');
    Route::post('promotions/store', [PromotionController::class, 'store'])->name('promotions.store');
    Route::get('promotions/{promotion}/edit', [PromotionController::class, 'edit'])->name('promotions.edit');
    Route::put('promotions/{promotion}/update', [PromotionController::class, 'update'])->name('promotions.update');
    Route::delete('promotions/{promotion}/destroy', [PromotionController::class, 'destroy'])->name('promotions.destroy');
});
// Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
Auth::routes();


Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
// }}








// admin
// Route::group(['prefix' => 'admin'], function () {
//     Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
// });


//checkout
// Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout.index');
// Route::post('/checkout/process', [CheckoutController::class, 'processCheckout'])->name('checkout.process');

// Route::get('/order/success', function () {
//     return view('checkout.success');
// })->name('order.success');


// //mini cart











// route cũ không dùng

// login-logout-register-forgetpassword
// Route::get('/login',[UserController::class,'login'])->name('login');
// Route::post('/login',[UserController::class,'postlogin']);
// Route::get('/register',[UserController::class,'register'])->name('register');
// Route::post('/register',[UserController::class,'postRegister']);
// Route::post('/logout',[UserController::class, 'logout'])->name('logout');
// Route::get('/test-email',[UserController::class,'testEmail']);
// Route::get('/forget-password',[UserController::class,'forgetPass'])->name('customer.forgetPass');
// Route::post('/forget-password',[UserController::class,'postForgetPassword']);
// Route::get('/get-password/customer/{token}',[UserController::class,'getPass'])->name('customer.getPass');
// Route::post('/get-password/customer/{token}',[UserController::class,'postGetPass']);
