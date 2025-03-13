<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Job;
use App\Models\Line;
use App\Models\User;
use App\Models\Scope;
use App\Models\Contract;
use App\Models\Material;
use Illuminate\Database\Seeder;
use App\Models\MaterialCategory;
use App\Models\MaterialEffectiveDate;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminSeeder::class);
        $this->call(SqlSeeder::class);
        User::factory(50)->create();

        // Initial Contract guaranteed
        MaterialEffectiveDate::factory()->create([
            'contract_id' => NULL,
            'effective_date' => fake()->randomElement(['2014-01-01', '2017-01-01', '2020-01-01', '2023-01-01'])
        ]);

        // Creates 15 contracts and each contract has between 1 and 3 related dates.
        Contract::factory(15)->create()->each(function ($contract) {
            MaterialEffectiveDate::factory(rand(1, 3))->create(['contract_id' => $contract->id]);
        });

        MaterialCategory::factory(10)->sequence(fn(Sequence $sequence) => ['Name' => 'Category #' . $sequence->index + 1])->create();
        Material::factory(50)->create();

        // Job::factory(100)->create();
        // Scope::factory(120)->create();
        // Line::factory(500)->create();
    }
}
