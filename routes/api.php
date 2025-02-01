<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::apiResource('roles', RoleController::class);

Route::post('/register', [AuthController::class, 'registerAdminUser']);
Route::post('/login ', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware('auth:sanctum');

/**
 * Function related to the Auth Controller
 * 
 */
Route::controller(AuthController::class)
  ->prefix('v1/auth')

  ->group(function () {
    Route::post('/register', 'registerAdminUser');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware(['auth:sanctum']);
  });
