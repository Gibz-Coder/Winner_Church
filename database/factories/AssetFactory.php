<?php

namespace Database\Factories;

use App\Enums\AssetStatus;
use App\Models\Asset;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Asset>
 */
class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => fake()->words(2, true),
            'serial_number' => fake()->optional()->bothify('SN-####-????'),
            'model_number' => fake()->optional()->bothify('MOD-###'),
            'brand' => fake()->company(),
            'description' => fake()->optional()->sentence(),
            'purchase_date' => fake()->optional()->dateTimeBetween('-5 years')?->format('Y-m-d'),
            'cost' => fake()->optional()->randomFloat(2, 50, 5000),
            'status' => fake()->randomElement(AssetStatus::cases()),
            'current_location' => fake()->randomElement([
                'Main Sanctuary', 'Media Room', 'Stage', 'Storage', 'Choir Stand',
            ]),
            'assigned_ministry' => fake()->randomElement([
                'Media & Sound', 'Music & Worship', 'Administration', 'Worship Support', 'Transportation',
            ]),
            'image' => null,
            'qr_code' => null,
            'notes' => fake()->optional()->paragraph(),
        ];
    }
}
