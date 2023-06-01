<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DiskController;
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

Route::get('/disk', [DiskController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);
    // Route::get('/disk', [DiskController::class, 'index']);
});
