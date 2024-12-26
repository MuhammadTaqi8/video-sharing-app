<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EngagementController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RatingController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/videos', [VideoController::class, 'upload']);
    Route::put('/videos/{id}', [VideoController::class, 'update']);
    Route::delete('/videos/{id}', [VideoController::class, 'delete']);
    
    Route::post('/videos/{id}/like', [EngagementController::class, 'like']);
    Route::post('/videos/{id}/share', [EngagementController::class, 'share']);
    
    Route::post('/videos/{id}/comments', [CommentController::class, 'store']);
    
    Route::post('/videos/{id}/rate', [RatingController::class, 'rate']);
});

Route::post('/upload', [VideoController::class, 'upload']);

Route::get('/videos', [VideoController::class, 'index']);
Route::get('/videos/{id}', [VideoController::class, 'show']);
Route::get('/videos/search', [VideoController::class, 'search']);

Route::get('/videos/{id}/comments', [CommentController::class, 'index']);
Route::get('/videos/{id}/rating', [RatingController::class, 'show']);

Route::post('/videos/cache-trending', [VideoController::class, 'cacheTrendingVideos']);
