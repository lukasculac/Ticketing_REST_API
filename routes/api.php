<?php

use App\Http\Controllers\Api\V1\FileController;
use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\WorkerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    Route::apiResource('tickets', TicketController::class);
    Route::apiResource('workers', WorkerController::class);
    Route::apiResource('files', FileController::class);
});

