<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;

use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\ShareController;
use App\Http\Controllers\API\FriendshipController;
use App\Http\Controllers\API\MyPostController;
use App\Http\Controllers\API\CustomerAuthController;
use App\Http\Controllers\API\HospitalController;
use App\Http\Controllers\Api\CategoryController;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/friends', [FriendshipController::class, 'index']);
    Route::post('/friends/{id}', [FriendshipController::class, 'sendRequest']);
    Route::get('/friend-requests', [FriendshipController::class, 'requests']);
    Route::post('/friend-requests/{id}/respond', [FriendshipController::class, 'respond']);
});

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);



Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts', [PostController::class, 'store']);
});

Route::get('/categories', [CategoryController::class, 'index']);
Route::prefix('customer')->group(function () {
    Route::post('/register', [CustomerAuthController::class, 'register']);
    Route::post('/login', [CustomerAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', function () {
            return response()->json(auth()->user());
        });

        Route::get('/customer/profile', [CustomerAuthController::class, 'update']); // optional: add show method if needed
        Route::put('/customer/profile', [CustomerAuthController::class, 'update']);
        Route::post('/logout', [CustomerAuthController::class, 'logout']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('posts/{post}/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
});


Route::middleware('auth:sanctum')->group(function () {
    Route::post('posts/{post}/share', [ShareController::class, 'share'])->name('posts.share');
});

use App\Http\Controllers\API\LikeController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('posts/{post}/like', [LikeController::class, 'toggleLike'])->name('posts.like.toggle');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/myposts', [MyPostController::class, 'index']);
    Route::put('/myposts/{id}', [MyPostController::class, 'update']);
    Route::delete('/myposts/{id}', [MyPostController::class, 'destroy']);
});


use App\Http\Controllers\Api\RestaurantController;
Route::get('/restaurants', [RestaurantController::class, 'index']);
Route::get('/restaurants/{id}', [RestaurantController::class, 'show']);
Route::get('hospitals', [HospitalController::class, 'index']);
Route::get('hospitals/{hospital}', [HospitalController::class, 'show']);