<?php

use Illuminate\Support\Facades\Route;
use Modules\Brand\App\Http\Controllers\BrandController;




Route::group(['prefix' => 'admin'], function () {

    Route::group(['middleware' => 'admin.guest'], function () {
      Route::get('login', [App\Http\Controllers\admin\AdminLoginController::class, 'index'])->name('admin.login');
     Route::post('authenticate', [App\Http\Controllers\admin\AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });



    Route::group(['middleware' => 'admin.auth'], function () {



// brand routes
Route::get('/brands', [BrandController::class, 'index'])->name('brand.index');
Route::get('/brands/create', [BrandController::class, 'create'])->name('brand.create');
Route::post('/brands', [BrandController::class, 'store'])->name('brand.store');

Route::get('/brands/{brand}/edit', [BrandController::class, 'edit'])->name('brand.edit');
Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('brand.update');

Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brand.destroy');




});
});
