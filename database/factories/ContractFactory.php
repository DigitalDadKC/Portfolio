<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contract>
 */
class ContractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $effective_date = fake()->dateTime();
        $term_date = fake()->dateTimeBetween($effective_date->format('Y-m-d') . '+4 years', $effective_date->format('Y-m-d') . '+4 years');
        return [
            'Name' => "Contract " . ucfirst(fake()->words(1, true)),
            'Contract_No' => fake()->randomNumber(8, false),
            'Effective_date' => $effective_date,
            'Termination_Date' => $term_date,
            'Admin_Fee' => fake()->randomFloat(4, 0, 1),
            'Discount' => fake()->randomFloat(3, 0, 0.3)
        ];
    }
}
