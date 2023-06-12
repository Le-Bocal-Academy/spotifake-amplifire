<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SongController;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::get('/confirmEmail/{id}', 'confirmEmail')->name('confirmEmail');
    Route::get('/resendEmailConfirmation/{id}', 'resendEmailConfirmation')->name('resendEmailConfirmation');
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

    Route::controller(SongController::class)->group(function () {
        Route::get('/track/{id}', 'play')->name('track.play');
        Route::get('/album', 'getAlbums');
        Route::get('/album/{id}', 'getAlbumById');
        Route::get('/search', 'search');
    });
});
