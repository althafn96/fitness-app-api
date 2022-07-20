<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(50)->create();

        foreach(\App\Models\User::all() as $user) {
            for($i = 6; $i >= 0; $i--) {
                $user->dailySteps()->create([
                    'steps_count' => fake()->numberBetween($min = 5000, $max = 100000),
                    'start_time' => Carbon::now()->subDays($i)->startOfDay()->toDateTimeString(),
                    'end_time' => Carbon::now()->subDays($i)->endOfDay()->toDateTimeString()
                ]);
            }
        }
    }
}
