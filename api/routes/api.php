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

Route::get('/confirmEmail/{id}', [AuthController::class, 'confirmEmail'])->name('confirmEmail');

Route::get('/resendEmailConfirmation/{id}', [AuthController::class, 'resendEmailConfirmation'])->name('resendEmailConfirmation');

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login')->name('login');
    Route::post('/forgotPassword', 'forgotPassword');
    Route::post('/resetPassword', 'resetPassword');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/logout', [AuthController::class, 'logout']);

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
