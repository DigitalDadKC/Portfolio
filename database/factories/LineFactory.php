<?php

namespace Database\Factories;

use DateTime;
use DateInterval;
use App\Models\Scope;
use App\Models\UnitOfMeasurement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Line>
 */
class LineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => $this->faker->word(),
            'unit_of_measurement_id' => UnitOfMeasurement::query()->inRandomOrder()->first()->id,
            'days' => $this->faker->randomDigit(),
            'price' => $this->faker->randomNumber(4),
            'quantity' => $this->faker->randomFloat(1, 0, 5000),
            'total' => $this->faker->randomFloat(2),
            'formatted_total' => '$' . $this->faker->randomNumber(3),
            'scope_id' => Scope::query()->inRandomOrder()->first()->id
        ];
    }
}
