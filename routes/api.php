<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\ProductCategoriesController;
use App\Http\Controllers\Api\ProductsController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VariationController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
// Handle CORS preflight requests
// Route::options('{any}', function () {
//   return response()->noContent()
//       ->header('Access-Control-Allow-Origin', 'http://localhost:5173')
//       ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
//       ->header('Access-Control-Allow-Headers', 'Authorization, Content-Type, X-Requested-With')
//       ->header('Access-Control-Allow-Credentials', 'true');
// })->where('any', '.*');

Route::apiResource('roles', RoleController::class);

// Route::post('/register', [AuthController::class, 'registerAdminUser']);
// Route::post('/login ', [AuthController::class, 'login']);

// Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Route::get('/user', function (Request $request) {
//   dd($request);
// });

/**
 * Function related to the Auth Controller
 * 
 */
Route::controller(AuthController::class)
  ->prefix('v1/auth')
  ->group(function () {
    Route::post('/register', 'registerAdminUser');
    Route::post('/login', 'login');
    // Route::get('/whoamI','whoamI');
    Route::middleware('auth:sanctum')->group(function () {
      Route::post('/logout', 'logout');
      Route::get('/whoamI', 'whoamI');
    });
  });

Route::controller(UserController::class)
  ->middleware('auth:sanctum')
  ->prefix('v1/users')
  ->group(function () {
    Route::get('/', 'index');
    // Route::post('/store', 'store');
    Route::get('/show/{id}', 'show');
    // Route::post('/update/{id}', 'update');
    // Route::delete('/destroy/{id}', 'destroy');
    // Route::post('/restore/{id}', 'restore');
    Route::delete('/delete/{id}', 'forceDelete');
    Route::post('userStatus/update/{id}', 'userStatusUpdate');
  });

// Route::controller(BrandController::class)
//   // ->middleware('auth:sanctum')
//   ->prefix('v1/brand')
//   ->group(function () {
//     Route::get('/', 'index')->name('brand.index');
//     Route::post('/store', 'store')->name('brand.store');
//     Route::get('/show/{id}', 'show')->name('brand.show');
//     Route::post('/update/{id}', 'update')->name('brand.update');
//     Route::delete('/destroy/{id}', 'destroy')->name('brand.destroy');
//     Route::post('/restore/{id}', 'restore')->name('brand.restore');
//     Route::delete('/delete/{id}', 'forceDelete')->name('brand.forceDelete');
//     Route::post('/status/update/{id}', 'statusUpdate')
//   });

Route::controller(BrandController::class)
  ->middleware('auth:sanctum')
  ->prefix('v1/brand')
  ->group(function () {
    Route::get('/', 'index');
    Route::post('/store', 'store');
    Route::get('/show/{id}', 'show');
    Route::post('/update/{id}', 'update');
    Route::delete('/destroy/{id}', 'destroy');
    Route::post('/restore/{id}', 'restore');
    Route::delete('/delete/{id}', 'forceDelete');
    Route::post('/status/update/{id}', 'statusUpdate');
  });

// Route::apiResource('categories', ProductCategoriesController::class)->middleware('auth:sanctum');

Route::controller(ProductCategoriesController::class)
  ->middleware('auth:sanctum')
  ->prefix('v1/categories')
  ->group(function () {
    Route::get('/', 'index');
    Route::post('/store', 'store');
    Route::get('/show/{id}', 'show');
    Route::post('/update/{id}', 'update');
    Route::delete('/destroy/{id}', 'destroy');
    Route::post('/restore/{id}', 'restore');
    Route::delete('/delete/{id}', 'forceDelete');
  });

Route::controller(ProductsController::class)
  ->middleware('auth:sanctum')
  ->prefix('v1/products')
  ->group(function () {
    Route::get('/', 'index');
    Route::post('/store', 'store');
    Route::get('/show/{id}', 'show');
    Route::post('/update/{id}', 'update');
    Route::delete('/destroy/{id}', 'destroy');
    Route::post('/restore/{id}', 'restore');
    Route::delete('/delete/{id}', 'forceDelete');
  });

// Route::apiResource('variation', VariationController::class)->middleware('auth:sanctum');
Route::controller(VariationController::class)
  ->middleware('auth:sanctum')
  ->prefix('v1/variation')
  ->group(function () {
    Route::get('/', 'index');
    Route::post('/store', 'store');
    Route::get('/show/{id}', 'show');
    Route::post('/update/{id}', 'update');
    Route::delete('/destroy/{id}', 'destroy');
    Route::post('/restore/{id}', 'restore');
    Route::delete('/delete/{id}', 'forceDelete');
  });
