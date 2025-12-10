<?php

namespace Database\Seeders;

use App\Models\SensorData;
use Database\Factories\SensorDataFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateRandomSensorData extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate the table to start fresh
        SensorData::truncate();
        
        // Reset the factory's timestamp tracker
        SensorDataFactory::resetTimestamp();
        
        // Create 100 entries for sensor_name 1 with sequential timestamps
        SensorData::factory(100)->create([
            'sensor_name' => 1,
        ]);
        
        // Reset timestamp tracker to start from the same time for sensor 2
        SensorDataFactory::resetTimestamp();
        
        // Create 100 entries for sensor_name 2 with sequential timestamps (same time points as sensor 1)
        SensorData::factory(100)->create([
            'sensor_name' => 2,
        ]);
    }
}
