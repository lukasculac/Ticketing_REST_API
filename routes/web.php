<?php

use App\Http\Controllers\Api\V1\FileController;
use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\WorkerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

