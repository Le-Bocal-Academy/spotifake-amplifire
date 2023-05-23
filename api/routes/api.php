<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Test;

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
Route::post('/forgotPassword', [AuthController::class, 'forgotPassword'])->name('password.reset');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/test', [AuthController::class, 'test']);
    Route::get('/logout', [AuthController::class, 'logout']);

    // rentrer le fichier songs.json dans la base de données et faire une route qui renvoie tous les sons
});
