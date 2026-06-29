<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\MaintenanceLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MaintenanceLog>
 */
class MaintenanceLogFactory extends Factory
{
    protected $model = MaintenanceLog::class;

    public function definition(): array
    {
        return [
            'asset_id' => Asset::factory(),
            'maintenance_type' => fake()->randomElement(['preventive', 'repair', 'calibration', 'inspection']),
            'description' => fake()->sentence(),
            'cost' => fake()->randomFloat(2, 50, 1000),
            'start_date' => fake()->dateTimeBetween('-2 months', 'now'),
            'status' => 'scheduled',
        ];
    }
}
