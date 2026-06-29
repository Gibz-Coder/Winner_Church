<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\BorrowRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BorrowRequest>
 */
class BorrowRequestFactory extends Factory
{
    protected $model = BorrowRequest::class;

    public function definition(): array
    {
        return [
            'asset_id' => Asset::factory(),
            'user_id' => User::factory(),
            'status' => 'pending',
            'borrow_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'expected_return_date' => fake()->dateTimeBetween('now', '+1 week'),
            'borrow_condition' => fake()->sentence(),
        ];
    }
}
