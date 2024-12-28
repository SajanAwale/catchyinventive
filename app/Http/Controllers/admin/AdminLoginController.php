<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    public function index() {
        return view('admin.login');
    }

    public function authenticate() {
        return redirect()->route('admin.dashboard');
    }
}
