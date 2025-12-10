<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorDataController;

Route::get('/sensor-data', [SensorDataController::class, 'index']);
Route::post('/sensor-data', [SensorDataController::class, 'store']);

