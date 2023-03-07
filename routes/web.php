<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('products', \App\Http\Controllers\ProductsController::class)->only(['index', 'show']);
Route::resource('categories', \App\Http\Controllers\CategoriesController::class)->only(['index', 'show']);

Route::name('admin.')->prefix('admin')->middleware(['role:admin|editor'])->group(function () {
    Route::get('dashboard', \App\Http\Controllers\Admin\DashboardController::class)->name('dashboard');

    Route::resource('products', \App\Http\Controllers\Admin\ProductsController::class)->except(['show']);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoriesController::class)->except(['show']);
});

Route::name('ajax.')->middleware('auth')->prefix('ajax')->group(function () {
    Route::group(['role:admin|editor'], function () {
        Route::delete('images/{image}', \App\Http\Controllers\Ajax\RemoveImageController::class)->name('images.delete');
    });
});

Route::name('account.')->prefix('account')->middleware('role:customer')->group(function () {
    Route::get('/', [\App\Http\Controllers\Account\UsersController::class, 'index'])->name('index');
    Route::get('{user}/edit', [\App\Http\Controllers\Account\UsersController::class, 'edit'])->name('edit')
        ->middleware('can:view,user');
    Route::put('{user}/update', [\App\Http\Controllers\Account\UsersController::class, 'update'])->name('update');
    Route::get('wishlist', \App\Http\Controllers\Account\WishListController::class)->name('wishlist');
});

Route::name('cart.')->prefix('cart')->group(function () {
    Route::get('/', [\App\Http\Controllers\CartController::class, 'index'])->name('index');
    Route::post('{product}', [\App\Http\Controllers\CartController::class, 'add'])->name('add');
    Route::delete('/', [\App\Http\Controllers\CartController::class, 'remove'])->name('remove');
    Route::post('{product}/count', [\App\Http\Controllers\CartController::class, 'countUpdate'])->name('count.update');
});

Route::group(['auth'], function () {
    Route::post('wishlist/{product}', [\App\Http\Controllers\WishListController::class, 'add'])->name('wishlist.add');
    Route::delete('wishlist/{product}', [\App\Http\Controllers\WishListController::class, 'remove'])->name('wishlist.remove');
    Route::get('checkout', function () {
        return view('checkout/index');
    })->name('checkout');

    Route::prefix('paypal')->name('paypal.')->group(function () {
        Route::post('order/create', [\App\Http\Controllers\Payments\PaypalController::class, 'create'])->name('orders.create');
        Route::post('order/{orderId}/capture', [\App\Http\Controllers\Payments\PaypalController::class, 'capture'])->name('orders.capture');
        Route::get('order/{orderId}/thankYou', [\App\Http\Controllers\Payments\PaypalController::class, 'thankYou'])->name('orders.thankYou');
    });
});
