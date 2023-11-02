<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HiredFreelancer>
 */
class HiredFreelancerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'freelancer_id' => fake()->numberBetween(),
            'client_id' => fake()->numberBetween(),
            'job_id' => fake()->numberBetween(),
            'hire_date' => new \DateTime
        ];
    }
}
