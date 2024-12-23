<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/login',[AdminLoginController::class,'index'])->name('admin.login');
Route::post('/admin/authenticate',[AdminLoginController::class,'authenticate'])->name('admin.authenticate');

Route::get('/admin/dashboard',[DashboardController::class,'index'])->name('admin.dashboard');