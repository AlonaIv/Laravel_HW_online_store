<?php

use Illuminate\Support\Facades\Route;

Route::resource('products', \App\Http\Controllers\Api\V2\ProductsController::class);
Route::resource('categories', \App\Http\Controllers\Api\V2\CategoriesController::class);
