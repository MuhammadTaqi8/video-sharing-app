<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EngagementController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RatingController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/register', [AuthController::class, 'register']); // Consumer or Creator registration
Route::post('/login', [AuthController::class, 'login']);       // User login
Route::post('/logout', [AuthController::class, 'logout']);     // User logout (JWT or session invalidation)

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/videos', [VideoController::class, 'store']);         // Upload a video (Creators only)
    Route::put('/videos/{id}', [VideoController::class, 'update']);    // Update video details
    Route::delete('/videos/{id}', [VideoController::class, 'destroy']); // Delete a video
});

Route::get('/videos', [VideoController::class, 'index']);             // List all videos
Route::get('/videos/{id}', [VideoController::class, 'show']);         // Get a specific video
Route::get('/videos/search', [VideoController::class, 'search']);     // Search for videos



Route::post('/videos/{id}/like', [EngagementController::class, 'like'])->middleware('auth:sanctum');
Route::post('/videos/{id}/share', [EngagementController::class, 'share'])->middleware('auth:sanctum');

Route::post('/videos/{id}/comments', [CommentController::class, 'store'])->middleware('auth:sanctum'); // Add a comment
Route::get('/videos/{id}/comments', [CommentController::class, 'index']);                             // List all comments



Route::post('/videos/{id}/rate', [RatingController::class, 'rate'])->middleware('auth:sanctum'); // Add or update a rating
Route::get('/videos/{id}/rating', [RatingController::class, 'show']);                            // Show average rating
