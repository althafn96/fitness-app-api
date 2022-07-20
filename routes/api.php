<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DailyStepsController;
use App\Http\Controllers\LeaderBoardController;
use Illuminate\Http\Request;
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


Route::prefix('v1')->group(function() {

    //public routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    //protected routes
    Route::middleware('auth:sanctum')->group(function() {

        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/leaderboard', LeaderBoardController::class);

        Route::apiResource('/daily-steps', DailyStepsController::class, ['only' => ['index', 'store']]);
    });

});

