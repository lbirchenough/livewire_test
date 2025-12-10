<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Home;
use App\Livewire\SensorData;

Route::get('/', Home::class)->name('login');
Route::get('/sensor-data', SensorData::class)->middleware('auth');


