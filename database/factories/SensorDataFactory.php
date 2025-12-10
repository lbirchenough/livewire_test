<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SensorData>
 */
class SensorDataFactory extends Factory
{
    /**
     * Track the last timestamp to ensure 1 minute spacing
     */
    private static ?\DateTime $lastTime = null; //This is the key, it's a static property so it's shared across all instances of the factory

    /**
     * Reset the timestamp tracker (useful when truncating and reseeding)
     */
    public static function resetTimestamp(): void
    {
        self::$lastTime = null;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Sequential timestamp logic (commented out for now)
        // Initialize with a time 1 week ago if this is the first record
        if (self::$lastTime === null) {
            self::$lastTime = new \DateTime('-1 week');
        } else {
            // Increment by 1 minute for each subsequent record
            self::$lastTime->modify('+1 minute');
        }

        return [
            'sensor_name' => $this->faker->randomElement([1, 2]),
            'value' => $this->faker->randomFloat(2, 10, 100),
            'type' => 'pressure',
            'unit' => 'kpa',
            'time' => clone self::$lastTime, // Clone ensures each record gets its own copy of the timestamp
        ];
    }
}
