<?php

namespace Database\Seeders;

use App\Models\Bundesland;
use Illuminate\Database\Seeder;

class BundeslandSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Bundesland::insert([
      ["id" => 1, "name" => "Thüringen", "short_name" => "TH"],
      ["id" => 2, "name" => "Sachsen", "short_name" => "SN"],
      ["id" => 3, "name" => "Berlin", "short_name" => "BE"],
    ]);
  }
}
