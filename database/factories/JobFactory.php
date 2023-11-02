<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => fake()->text(),
            'work_fields' => 'IT',
            'job_title' => fake()->text(99),
            'pay_amount' => fake()->numberBetween(100, 100000),
            'posted_time' => new \DateTime,
            'user_id' => User::factory()->create()->id,
        ];
    }
}
