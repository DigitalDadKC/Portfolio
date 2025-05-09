<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Raleigh',
            'email' => 'raleighgroesbeck@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('KSUwildcat#5')
        ]);
    }
}