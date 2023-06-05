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
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgotPassword', [AuthController::class, 'forgotPassword']);
Route::post('/resetPassword', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);

    Route::get('/playlist', [PlaylistController::class, 'getAllPlaylist']);
    Route::post('/playlist/create', [PlaylistController::class, 'createPlaylist']);
    Route::post('/playlist/addTrack', [PlaylistController::class, 'addTrack']);
    Route::post('/playlist/deleteTrack', [PlaylistController::class, 'deleteTrack']);
    Route::post('/playlist/deletePlaylist', [PlaylistController::class, 'deletePlaylist']);
    Route::put('/playlist/renamePlaylist', [PlaylistController::class, 'renamePlaylist']);

    Route::get('/track/{id}', [TrackController::class, 'play'])->name('track.play');

    Route::get('/album/{id}', [AlbumController::class, 'getAlbumById']);

    Route::get('/search', [SearchController::class, 'search']);
});
