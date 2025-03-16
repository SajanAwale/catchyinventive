<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
      Route::get('/whoamI','whoamI');
    });
  });

Route::controller(BrandController::class)
  ->prefix('v1/brand')
  ->group(function () {
    Route::get('/', 'index');
    Route::post('/store', 'store');
    Route::get('/show/{brand}', 'show');
    Route::put('/update/{brand}', 'update');
    Route::delete('/destroy/{brand}', 'destroy');
  });
