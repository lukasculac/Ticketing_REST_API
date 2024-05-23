<?php

use App\Http\Controllers\Api\V1\AgentController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\FileController;
use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\WorkerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
Route::get('/workers', function (Request $request) {
    return $request->worker();
})->middleware('auth:sanctum');
*/
Route::post('v1/register', [AuthController::class, 'register']);
Route::post('v1/login', [AuthController::class, 'login']);



Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    Route::apiResource('tickets', TicketController::class);
    Route::apiResource('workers', WorkerController::class);
    Route::apiResource('files', FileController::class);
    Route::apiResource('agents', AgentController::class);

    //Route::post('workers', [WorkerController::class, 'store']);
    //Route::post('tickets/bulk', [WorkerController::class, 'bulkStore']);
    Route::delete('/agents/{agent}', [AgentController::class, 'destroy']);
    Route::delete('/workers/{worker}', [WorkerController::class, 'destroy']);
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/store_ticket', [TicketController::class, 'store'])->middleware('auth:worker');



});

