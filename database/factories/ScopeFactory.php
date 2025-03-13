<?php

namespace Database\Factories;

use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ScopeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'area' => $this->faker->randomNumber(5),
            'total' => $this->faker->randomFloat(2),
            'formatted_total' => '$' . $this->faker->randomNumber(3) . ',' . $this->faker->randomNumber(3),
            'days' => $this->faker->randomNumber(2),
            'job_id' => Job::query()->inRandomOrder()->first()->id
        ];
    }
}
