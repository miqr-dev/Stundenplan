<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\Subject;
use App\Models\Template;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Template::insert([
      ["id" => 1, "name" => "APO"],
      ["id" => 2, "name" => "BAMF"],
      ["id" => 3, "name" => "BPW"],
      ["id" => 4, "name" => "BUS"],
      ["id" => 5, "name" => "E-Com"],
      ["id" => 6, "name" => "FOSI"],
      ["id" => 7, "name" => "GruKo"],
      ["id" => 8, "name" => "IBO"],
      ["id" => 9, "name" => "IK"],
      ["id" => 10, "name" => "ISO"],
      ["id" => 11, "name" => "KMQ"],
      ["id" => 12, "name" => "MIBO"],
      ["id" => 13, "name" => "MIFW"],
      ["id" => 14, "name" => "MISO"],
      ["id" => 15, "name" => "MWe"],
      ["id" => 16, "name" => "OSI"],
      ["id" => 17, "name" => "PAA"],
      ["id" => 18, "name" => "SAB"]
    ]);

    $faker = Factory::create();

    $templates = Template::all();

    foreach ($templates as $template) {
      $template->subjects()->sync($faker->randomElements(
        Subject::pluck('id')->toArray(),
        $faker->numberBetween(1, 5)
      ));
    }
  }
}
