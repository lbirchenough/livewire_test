<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SensorData;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SensorDataController extends Controller
{
    public function index(): JsonResponse
    {
        $sensorData = SensorData::orderBy('time', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $sensorData,
            'count' => $sensorData->count(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        // Validate incoming data
        $validated = $request->validate([
            'sensor_name' => 'required|integer|in:1,2',
            'value' => 'required|numeric',
            'unit' => 'required|string',
            'type' => 'required|string',
            'time' => 'nullable|date',
        ]);

        // If time is not provided, use current time
        if (!isset($validated['time'])) {
            $validated['time'] = now();
        }

        // Create the sensor data record
        $sensorData = SensorData::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Sensor data stored successfully',
            'data' => $sensorData,
        ], 201);
    }
}
