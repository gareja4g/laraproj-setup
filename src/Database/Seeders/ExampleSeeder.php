<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ExampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 50) as $index) {
            DB::table('examples')->insertGetId([
                'name' => $faker->name,
                'descriptions' => $faker->paragraph,
                'image' => "images/dummy.jpeg",
                'price' => $faker->randomFloat(2, 1, 1000),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
