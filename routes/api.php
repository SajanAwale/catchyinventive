<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('roles', RoleController::class);

Route::post('/register', [AuthController::class, 'registerAdminUser']);
Route::post('/login ', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Route::group([
//   'prefix' => 'v1',
//   'as' => 'api.',
//   'namespace' => 'Api\V1\Admin',
//   'middleware' => ['auth:sanctum']
// ], function (){
//   Route::apiResource('projects', 'ProjectsApiController');
// });

// Route::get('/', function () {
    
// });