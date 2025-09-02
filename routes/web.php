<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\LangController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WriterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\HospitalController;

Route::get('/locale/{lang}', [LangController::class, 'setlocale'])->name('lang.switch');

Route::get('/', function () {
    return redirect('/login');
});

Route::get('auth/google', fn() => Socialite::driver('google')->redirect())->name('google.login');
Route::get('auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->user();

    $user = User::updateOrCreate(
        ['email' => $googleUser->getEmail()],
        [
            'name' => $googleUser->getName(),
            'google_id' => $googleUser->getId(),
            'password' => bcrypt(Str::random(24)),
        ]
    );

    if (!$user->hasAnyRole(['Super Admin', 'Admin', 'Writer', 'Customer'])) {
        $user->assignRole('Customer');
    }

    Auth::login($user);
    return match (true) {
        $user->hasRole('Super Admin') => redirect()->route('superadmin.dashboard'),
        $user->hasRole('Admin')       => redirect()->route('admin.dashboard'),
        $user->hasRole('Writer')      => redirect()->route('writer.dashboard'),
        default                       => redirect()->route('home'),
    };
});

Auth::routes();
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');


Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');
Route::middleware(['auth', 'super_admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/', [SuperAdminController::class, 'index'])->name('dashboard');
    Route::resource('posts', PostController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('comments', CommentController::class);
    Route::resource('restaurants', RestaurantController::class);
    Route::resource('hospitals', HospitalController::class);
});

Route::middleware(['auth', 'super_admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('/store', [UserController::class, 'store'])->name('store');
    Route::post('users/{id}/toggle-role', [UserController::class, 'toggleRole'])->name('users.toggle-role');
    Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});


Route::middleware(['auth', 'admin_only'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('posts', PostController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('comments', CommentController::class);
    Route::resource('restaurants', RestaurantController::class);
    Route::resource('hospitals', HospitalController::class);
});

// Writer
Route::middleware(['auth', 'writer_only'])->prefix('writer')->name('writer.')->group(function () {
    Route::get('/dashboard', [WriterController::class, 'index'])->name('dashboard');
    Route::resource('posts', PostController::class);
    Route::resource('comments', CommentController::class);
});

// Customer
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::resource('posts', PostController::class);
    Route::resource('comments', CommentController::class);
});
