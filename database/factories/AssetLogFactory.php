<?php

namespace Database\Factories;

use App\Enums\AssetLogAction;
use App\Models\Asset;
use App\Models\AssetLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AssetLog>
 */
class AssetLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'asset_id' => Asset::factory(),
            'user_id' => User::factory(),
            'action' => fake()->randomElement(AssetLogAction::cases()),
            'description' => fake()->sentence(),
        ];
    }
}
