<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\Subject;
use App\Models\Template;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Subject::insert([
      ["id" => 1, "name" => "Bürowirtschaft & Informationsmanagement", "color" => "#e6b8af"],
      ["id" => 2, "name" => "Rechnungswesen & Buchführung", "color" => "#f9cb9c"],
      ["id" => 3, "name" => "Materialwirtschaft & Lagerhaltung", "color" => "#d0e0e3"],
      ["id" => 4, "name" => "Berufliches Anwendungstraining", "color" => "#c9daf8"],
      ["id" => 5, "name" => "Personalwesen", "color" => "#dd7e6b"],
      ["id" => 6, "name" => "Kommunikations- & Bewerbungsschulung", "color" => "#93c47d"],
      ["id" => 7, "name" => "Lehr- & Lerndidaktik", "color" => "#6fa8dc"],
      ["id" => 8, "name" => "Wirtschaftsprozesse & betriebliche Grundlagen", "color" => ""],
      ["id" => 9, "name" => "Wirtschaftsrecht", "color" => "#c27ba0"],
      ["id" => 10, "name" => "Wirtschaftsenglisch", "color" => "#b45f06"],
      ["id" => 11, "name" => "Betriebliches Gesundheitsmanagement", "color" => "#b7b7b7"]
    ]);

    $templates = Template::all();
    $faker = Factory::create();
    foreach ($templates as $template) {
      $template->subjects()->sync($faker->randomElements(
        Subject::pluck('id')->toArray(),
        $faker->numberBetween(1, 5)
      ));
    }
  }
}
