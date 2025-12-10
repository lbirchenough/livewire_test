<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    use HasFactory; //Adding this lets you use the factory to create dummy data
    protected $fillable = [
        'sensor_name',
        'value',
        'unit',
        'type',
        'time',
    ];

    // protected $casts = [
    //     'value' => 'float',
    //     'time' => 'datetime',
    // ];
}
