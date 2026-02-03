<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\App\Http\Controllers\ProductController;


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


Route::get('/products', [ProductController::class, 'index'])->name('admin.product.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('product.create');
Route::post('/products', [ProductController::class, 'store'])->name('product.store');

Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('product.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('product.destroy');

// Dropzone → Spatie media
Route::post('/products/{product}/media', [ProductController::class, 'uploadMedia'])->name('product.media.store');
Route::delete('/products/{product}/media/{media}', [ProductController::class, 'deleteMedia'])->name('product.media.destroy');
