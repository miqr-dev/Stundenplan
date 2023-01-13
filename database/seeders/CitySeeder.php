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
        ["id"=>1,"name"=>"Erfurt"],
        ["id"=>2,"name"=>"Suhl"],
        ["id"=>3,"name"=>"Leipzig"],
        ["id"=>4,"name"=>"Dresden"],
        ["id"=>5,"name"=>"Chemnitz"],
        ["id"=>6,"name"=>"Berlin"]
      ]);
    }
}
