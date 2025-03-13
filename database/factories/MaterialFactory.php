<?php

namespace Database\Factories;

use App\Models\MaterialCategory;
use App\Models\MaterialUnitSize;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Material>
 */
class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $unit_size_ids = MaterialUnitSize::pluck('id')->toArray();
        $category_ids = MaterialCategory::pluck('id')->toArray();
        return [
            'SKU' => strtoupper(fake()->randomLetter() . fake()->unique()->numberBetween(0, 10000)),
            'Name' => "Product " . fake()->words(2, true),
            'material_unit_size_id' => fake()->randomElement($unit_size_ids),
            '2023-01-01' => fake()->randomNumber(4),
            '2023-01-01-status' => fake()->randomElement(['New', 'Active', 'Removed', 'Obsolete', 'Discontinued']),
            '2020-01-01' => fake()->randomNumber(4),
            '2020-01-01-status' => fake()->randomElement(['New', 'Active', 'Removed', 'Obsolete', 'Discontinued']),
            '2017-01-01' => fake()->randomNumber(4),
            '2017-01-01-status' => fake()->randomElement(['New', 'Active', 'Removed', 'Obsolete', 'Discontinued']),
            '2014-01-01' => fake()->randomNumber(4),
            '2014-01-01-status' => fake()->randomElement(['New', 'Active', 'Removed', 'Obsolete', 'Discontinued']),
            'Discountable' => fake()->boolean(),
            'material_category_id' => fake()->randomElement($category_ids),
        ];
    }
}
