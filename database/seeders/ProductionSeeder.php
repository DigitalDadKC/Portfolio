<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Job;
use App\Models\Line;
use App\Models\Proposal;
use App\Models\Scope;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(StateSeeder::class);

        Customer::factory(50)->create();

        Job::factory(250)
            ->create()
            ->each(fn($job) => $job->proposals()->saveMany(Proposal::factory(mt_rand(1, 3))->create()
                ->each(fn($proposal) => $proposal->scopes()->saveMany(Scope::factory(mt_rand(1, 3))->create()
                    ->each(fn($scope) => $scope->lines()->saveMany(Line::factory(mt_rand(1, 3))
                    ->state(new Sequence(
                        fn(Sequence $sequence) => [
                            'order' => $sequence->index
                        ]
                    ))
                    ->create()))
                ))
            ));
    }
}
