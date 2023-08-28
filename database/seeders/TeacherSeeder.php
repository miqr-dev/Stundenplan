<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\City;
use App\Models\Subject;
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
    for ($i = 0; $i < 100; $i++) {
        Teacher::create([
            'name' => $faker->firstName,
            'surname' => $faker->lastName,
            'maxhourweek' => rand(20,40),
            'color' =>$faker->hexcolor,
            'email' => $faker->unique()->safeEmail,
        ]);
    }

     $teachers = Teacher::all();

        foreach ($teachers as $teacher) {
            $teacher->subjects()->sync($faker->randomElements(
                Subject::pluck('id')->toArray(),
                $faker->numberBetween(1, 3)
            ));
            $teacher->cities()->sync($faker->randomElement(
              City::pluck('id')->toArray(),
              $faker->numberBetween(1,3)
            ));    
        }
    }
}
