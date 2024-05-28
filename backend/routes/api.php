<?php

use App\Http\Controllers\Api\V1\AgentController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\FileController;
use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\WorkerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('v1/register', [AuthController::class, 'register']);
Route::post('v1/login', [AuthController::class, 'login']);
//Route::post('v1/logout', [AuthController::class, 'logout'])-> middleware('auth:sanctum');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('v1/logout', [AuthController::class, 'logout']);
    Route::post('v1/store_ticket', [TicketController::class, 'store']);
});
Route::post('v1/edit_ticket/{ticket}', [TicketController::class, 'update']);


Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    Route::apiResource('tickets', TicketController::class)->middleware('auth:sanctum');
    Route::apiResource('workers', WorkerController::class);
    Route::apiResource('files', FileController::class);
    Route::apiResource('agents', AgentController::class);

    Route::delete('/agents/{agent}', [AgentController::class, 'destroy']);
    Route::delete('/workers/{worker}', [WorkerController::class, 'destroy']);
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy']);
    Route::delete('/delete_file/{file}', [FileController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

