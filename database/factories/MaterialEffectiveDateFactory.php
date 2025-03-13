<?php

namespace Database\Factories;

use App\Models\Contract;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MaterialEffectiveDate>
 */
class MaterialEffectiveDateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'contract_id' => rand(1, Contract::count()),
            'effective_date' => fake()->randomElement(['2014-01-01', '2017-01-01', '2020-01-01', '2023-01-01'])
        ];
    }
}
