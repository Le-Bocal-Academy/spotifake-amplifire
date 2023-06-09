<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth:sanctum')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', [AuthController::class, 'resendEmail'])->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');

Route::middleware('verified')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/forgotPassword', 'forgotPassword');
        Route::post('/resetPassword', 'resetPassword');
    });
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);

    // Route::get('/playlist', [PlaylistController::class, 'getAllPlaylist']);
    // Route::post('/playlist/create', [PlaylistController::class, 'createPlaylist']);
    // Route::post('/playlist/addTrack', [PlaylistController::class, 'addTrack']);
    // Route::post('/playlist/deleteTrack', [PlaylistController::class, 'deleteTrack']);
    // Route::post('/playlist/deletePlaylist', [PlaylistController::class, 'deletePlaylist']);
    // Route::put('/playlist/renamePlaylist', [PlaylistController::class, 'renamePlaylist']);
    Route::controller(PlaylistController::class)->group(function () {
        Route::get('/playlist', 'getAllPlaylist');
        Route::post('/playlist/create', 'createPlaylist');
        Route::post('/playlist/addTrack', 'addTrack');
        Route::post('/playlist/deleteTrack', 'deleteTrack');
        Route::post('/playlist/deletePlaylist', 'deletePlaylist');
        Route::put('/playlist/renamePlaylist', 'renamePlaylist');
    });

    Route::get('/track/{id}', [TrackController::class, 'play'])->name('track.play');

    Route::get('/album', [AlbumController::class, 'getAlbums']);
    Route::get('/album/{id}', [AlbumController::class, 'getAlbumById']);

    Route::get('/search', [SearchController::class, 'search']);
});
