<?php

namespace Database\Seeders;

use App\Models\SensorData;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddNewRandomSensorData extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the most recent entry for sensor_name 1
        $mostRecentSensor1 = SensorData::where('sensor_name', 1)->orderBy('time', 'desc')->first();
        
        // Find the most recent entry for sensor_name 2
        $mostRecentSensor2 = SensorData::where('sensor_name', 2)->orderBy('time', 'desc')->first();
        
        // Calculate new timestamp for sensor 1 (1 minute after most recent, or 1 week ago if none exist)
        if ($mostRecentSensor1) {
            $newTimeSensor1 = new \DateTime($mostRecentSensor1->time);
            $newTimeSensor1->modify('+1 minute');
        } else {
            $newTimeSensor1 = new \DateTime('-1 week');
        }
        
        // Calculate new timestamp for sensor 2 (1 minute after most recent, or 1 week ago if none exist)
        if ($mostRecentSensor2) {
            $newTimeSensor2 = new \DateTime($mostRecentSensor2->time);
            $newTimeSensor2->modify('+1 minute');
        } else {
            $newTimeSensor2 = new \DateTime('-1 week');
        }
        
        // Create a new entry for sensor 1
        SensorData::factory()->create([
            'sensor_name' => 1,
            'time' => $newTimeSensor1,
        ]);
        
        // Create a new entry for sensor 2
        SensorData::factory()->create([
            'sensor_name' => 2,
            'time' => $newTimeSensor2,
        ]);
    }
}
