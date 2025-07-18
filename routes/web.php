<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LangController;
use App\Http\Controllers\SuperAdminController;

Route::get('/locale/{lang}', [LangController::class, 'setlocale']);

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('customers', CustomerController::class);
Route::resource('Category', CategoryController::class);

Route::middleware(['auth', 'super_admin'])->group(function () {
    Route::get('/superadmin/dashboard', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
});




