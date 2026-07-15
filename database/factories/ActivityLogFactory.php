<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    protected $model = ActivityLog::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'event' => $this->faker->randomElement(['login', 'logout', 'created', 'updated', 'deleted', 'restored', 'exported', 'imported']),
            'resource' => $this->faker->randomElement(['Acolhido', 'Agenda', 'User', 'Role']),
            'action' => $this->faker->randomElement(['view', 'create', 'update', 'delete']),
            'description' => $this->faker->sentence(),
            'old_values' => ['name' => $this->faker->firstName()],
            'new_values' => ['name' => $this->faker->firstName()],
            'ip' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'url' => $this->faker->url(),
            'method' => 'GET',
            'status' => 'success',
            'message' => 'Registro criado com sucesso.',
            'created_at' => now()->subHours(rand(1, 24)),
            'updated_at' => now()->subHours(rand(1, 24)),
        ];
    }
}
