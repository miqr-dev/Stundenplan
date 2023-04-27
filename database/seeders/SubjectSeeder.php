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
      ["id" => 1, "name" => "Bürowirtschaft & Informationsmanagement", "color" => "#e6b8af","default_soll" => 40],
      ["id" => 2, "name" => "Rechnungswesen & Buchführung", "color" => "#f9cb9c","default_soll" => 50],
      ["id" => 3, "name" => "Materialwirtschaft & Lagerhaltung", "color" => "#d0e0e3","default_soll" => 60],
      ["id" => 4, "name" => "Berufliches Anwendungstraining", "color" => "#c9daf8","default_soll" => 70],
      ["id" => 5, "name" => "Personalwesen", "color" => "#dd7e6b","default_soll" => 80],
      ["id" => 6, "name" => "Kommunikations- & Bewerbungsschulung", "color" => "#93c47d","default_soll" => 90],
      ["id" => 7, "name" => "Lehr- & Lerndidaktik", "color" => "#6fa8dc","default_soll" => 100],
      ["id" => 8, "name" => "Wirtschaftsprozesse & betriebliche Grundlagen", "color" => "","default_soll" => 115],
      ["id" => 9, "name" => "Wirtschaftsrecht", "color" => "#c27ba0","default_soll" => 25],
      ["id" => 10, "name" => "Wirtschaftsenglisch", "color" => "#b45f06","default_soll" => 40],
      ["id" => 11, "name" => "Betriebliches Gesundheitsmanagement", "color" => "#b7b7b7" ,"default_soll" => 40]
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
