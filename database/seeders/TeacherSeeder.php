<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    $faker = Factory::create();
    for ($i = 0; $i < 10; $i++) {
        Teacher::create([
            'name' => $faker->firstName,
            'surname' => $faker->lastName,
            'maxhourweek' => rand(20,40),
            'color' =>$faker->hexcolor,
            'email' => $faker->unique()->safeEmail,
        ]);
    }
    }
}
