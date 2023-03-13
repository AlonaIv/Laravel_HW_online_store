<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('auth', [\App\Http\Controllers\Api\AuthController::class, '__invoke'])->name('auth');

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    require __DIR__ . '/versions/v1.php';
});

Route::prefix('v2')->middleware('auth:sanctum')->group(function () {
    require __DIR__ . '/versions/v2.php';
});
