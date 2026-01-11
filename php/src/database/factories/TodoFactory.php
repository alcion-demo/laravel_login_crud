<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Todo;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // ユーザーを自動で作って紐付ける
            'user_id' => User::factory(), 
            'title' => $this->faker->sentence(3),
            'detail' => $this->faker->realText(20),
            'status' => 1,    // 未完了などを数値で
            'priority' => 1,  // 低などを数値で
            'deadline' => now()->addDays(7)->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '10:00',
        ];
    }
}
