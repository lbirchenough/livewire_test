<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Counter;
use App\Livewire\SensorData;
use App\Livewire\Welcome;


Route::get('/sensor-data', SensorData::class);
