<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorDataController;

// API routes protected with API key
// Requires X-API-Key header or api_key parameter
Route::middleware('api.key')->group(function () {
    Route::get('/sensor-data', [SensorDataController::class, 'index']);
    Route::post('/sensor-data', [SensorDataController::class, 'store']);
});

