<?php

namespace Database\Seeders;

use App\Models\Subject;
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
      ["id"=>1,"name"=>"Bürowirtschaft & Informationsmanagement"],
      ["id"=>2,"name"=>"Rechnungswesen & Buchführung"],
      ["id"=>3,"name"=>"Materialwirtschaft & Lagerhaltung"],
      ["id"=>4,"name"=>"Berufliches Anwendungstraining"],
      ["id"=>5,"name"=>"Personalwesen"],
      ["id"=>6,"name"=>"Kommunikations- & Bewerbungsschulung"],
      ["id"=>7,"name"=>"Lehr- & Lerndidaktik"],
      ["id"=>8,"name"=>"Wirtschaftsprozesse & betriebliche Grundlagen"],
      ["id"=>9,"name"=>"Wirtschaftsrecht"],
      ["id"=>10,"name"=>"Wirtschaftsenglisch"],
      ["id"=>11,"name"=>"Betriebliches Gesundheitsmanagement"]
    ]);
    }
}
