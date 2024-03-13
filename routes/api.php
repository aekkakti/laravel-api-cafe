<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WorkshiftController;
use Illuminate\Http\Request;
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

Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);
    // работа с пользователями
    Route::get('/user', [UsersController::class, 'showUsers']);
    Route::post('/user', [UsersController::class, 'addUser']);
    // работа со сменами
    Route::post('/work-shift', [WorkshiftController::class, 'createWorkshift']);
    Route::get('/work-shift/{workshift}/open', [WorkshiftController::class, 'openWorkshift']);
    Route::get('/work-shift/{workshift}/close', [WorkshiftController::class, 'closeWorkshift']);
    Route::post('/work-shift/{workshift}/user', [WorkshiftController::class, 'addWorkerToWorkshift']);
});
