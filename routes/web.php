<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\PermissionController;
use Illuminate\Http\Request;

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

Route::get('/', function () {
    return view('welcome');
});




Route::group(['prefix' => 'admin'], function () {

    Route::group(['middleware' => 'admin.guest'], function () {
      Route::get('login', [App\Http\Controllers\admin\AdminLoginController::class, 'index'])->name('admin.login');
     Route::post('authenticate', [App\Http\Controllers\admin\AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });



    Route::group(['middleware' => 'admin.auth'], function () {
        Route::get('dashboard', [App\Http\Controllers\admin\HomeController::class, 'index'])->name('admin.dashboard');
     Route::get('logout', [App\Http\Controllers\admin\HomeController::class, 'logout'])->name('admin.logout');

//category routes
Route::get('/categories', [App\Http\Controllers\admin\CategoryController::class, 'index'])->name('category.index');
Route::get('/categories/create', [App\Http\Controllers\admin\CategoryController::class, 'create'])->name('category.create');
Route::post('/categories', [App\Http\Controllers\admin\CategoryController::class, 'store'])->name('category.store');


Route::get('/categories/{category}/edit', [App\Http\Controllers\admin\CategoryController::class, 'edit'])->name('category.edit');
Route::put('/categories/{category}', [App\Http\Controllers\admin\CategoryController::class, 'update'])->name('category.update');
Route::delete('/categories/{category}', [App\Http\Controllers\admin\CategoryController::class, 'destroy'])->name('category.destroy');



     Route::post('/temp-images', [App\Http\Controllers\admin\TempImageController::class, 'store'])
            ->name('temp-images.store');

Route::get('/getSlug', function (Request $request) {
    $slug = '';

    if ($request->title) {
        $slug = Str::slug($request->title);
    }

    return response()->json([
        'status' => true,
        'slug' => $slug
    ]);
})->name('getSlug');




});

});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ↓↓↓ Customized routes

    Route::middleware(['permission:user_management_access'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('user.index');

        Route::get('/users/create', [UserController::class, 'create'])->name('user.create');
        Route::get('/users/edit/{user}', [UserController::class, 'edit'])->name('user.edit');
        Route::get('/users/show/{user}', [UserController::class, 'show'])->name('user.show');
        Route::post('/users/store', [UserController::class, 'store'])->name('user.store');
        Route::patch('/users/update/{user}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/users/delete/{user}', [UserController::class, 'destroy'])->name('user.destroy');

        Route::get('/users/model', [UserController::class, 'downloadModel'])->name('user.import.model');
        Route::post('/users/import', [UserController::class, 'import'])->name('user.import');
    });

    Route::middleware(['permission:role_management_access'])->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('role.index');

        Route::get('/roles/create', [RoleController::class, 'create'])->name('role.create');
        Route::get('/roles/edit/{role}', [RoleController::class, 'edit'])->name('role.edit');
        Route::get('/roles/show/{role}', [RoleController::class, 'show'])->name('role.show');
        Route::post('/roles/store', [RoleController::class, 'store'])->name('role.store');
        Route::patch('/roles/update/{role}', [RoleController::class, 'user'])->name('role.update');
        Route::delete('/roles/delete/{role}', [RoleController::class, 'destroy'])->name('role.destroy');
    });

    Route::middleware(['permission:permission_management_access'])->group(function () {
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permission.index');

        Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permission.create');
        Route::get('/permissions/edit/{permission}', [PermissionController::class, 'edit'])->name('permission.edit');
        Route::get('/permissions/show/{permission}', [PermissionController::class, 'show'])->name('permission.show');
        Route::post('/permissions/store', [PermissionController::class, 'store'])->name('permission.store');
        Route::patch('/permissions/update/{permission}', [PermissionController::class, 'permission'])->name('permission.update');
        Route::delete('/permissions/delete/{permission}', [PermissionController::class, 'destroy'])->name('permission.destroy');
    });
});

require __DIR__.'/auth.php';
