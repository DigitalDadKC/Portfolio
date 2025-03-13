<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
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
            'number' => $this->faker->unique()->randomNumber(5, true),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state_id' => State::query()->inRandomOrder()->first()->id,
            'total' => $this->faker->randomFloat(2, 500000, 10000000),
            'formatted_total' => '$' . $this->faker->randomNumber(3) . ',' . $this->faker->randomNumber(3) . ',' . $this->faker->randomNumber(3),
            'days' => $this->faker->randomNumber(3),
            'start_date' => $this->faker->dateTimeThisYear(),
            'notes' => $this->faker->sentence(),
            'created_at' => $this->faker->dateTimeThisYear()
        ];
    }
}
