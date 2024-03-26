<?php

use App\Http\Controllers\Api\v1\NoteController;
use App\Http\Controllers\Api\v1\PlantController;
use App\Http\Controllers\Api\v1\ForgotPasswordController;
use App\Http\Controllers\Api\v1\UserAuthController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//todo dodac do api powiadomienia, obsluga
Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\v1', 'middleware' => 'auth:sanctum'], function () {
    Route::apiResource('plant', PlantController::class);
    Route::apiResource('note', NoteController::class);
    Route::apiResource('user', UserController::class);
    Route::post('/auth/logout', [UserAuthController::class, 'logout'])
        ->name('logout');
});

Route::middleware('guest')->group(function () {
    Route::post('/auth/register', [UserAuthController::class, 'createUser']);
    Route::post('/auth/login', [UserAuthController::class, 'loginUser']);
    Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword'])
        ->name('password.email');
});
