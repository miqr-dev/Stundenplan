<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      City::insert([
        ["id"=>1,"bundesland_id" => 1, "name"=>"Erfurt"],
        ["id"=>2,"bundesland_id" => 1, "name"=>"Suhl"],
        ["id"=>3,"bundesland_id" => 2, "name"=>"Leipzig"],
        ["id"=>4,"bundesland_id" => 2, "name"=>"Dresden"],
        ["id"=>5,"bundesland_id" => 2, "name"=>"Chemnitz"],
        ["id"=>6,"bundesland_id" => 3, "name"=>"Berlin"],
        ["id"=>7,"bundesland_id" => 2, "name"=>"Döbeln"]
      ]);
    }
}
