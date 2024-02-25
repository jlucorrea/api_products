<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductBrandController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(ProductBrandController::class)->prefix('brands')->group(function() {
    Route::get('list', 'index');
    Route::get('show/{id}', 'show');
    Route::post('create', 'store');
    Route::put('edit/{id}', 'store');
    Route::delete('delete/{id}', 'deleteBrand');
});

Route::controller(ProductController::class)->prefix('products')->group(function() {
    Route::get('list', 'index');
    Route::post('create', 'store');
    Route::put('edit/{id}', 'store');
    Route::delete('delete/{id}', 'deleteProduct');
});