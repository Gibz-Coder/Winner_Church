<?php

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AuditLog>
 */
class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'action' => fake()->randomElement(['Created Asset', 'Updated Asset', 'Deleted Asset', 'Approved Request']),
            'auditable_type' => 'App\\Models\\Asset',
            'auditable_id' => fake()->randomDigitNotZero(),
            'ip_address' => fake()->ipv4(),
        ];
    }
}
