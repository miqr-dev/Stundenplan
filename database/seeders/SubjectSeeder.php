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
      ["id"=>1,"name"=>"Bürowirtschaft & Informationsmanagement IBO/OSI I"],
      ["id"=>2,"name"=>"Rechnungswesen & Buchführung IBO/OSI I"],
      ["id"=>3,"name"=>"Materialwirtschaft & Lagerhaltung IBO/OSI I"],
      ["id"=>4,"name"=>"Berufliches Anwendungstraining IBO/OSI I"],
      ["id"=>5,"name"=>"Personalwesen IBO/OSI I"],
      ["id"=>6,"name"=>"Kommunikations- & Bewerbungsschulung IBO/OSI I"],
      ["id"=>7,"name"=>"Lehr- & Lerndidaktik IBO/OSI I"],
      ["id"=>8,"name"=>"Wirtschaftsprozesse & betriebliche Grundlagen IBO/OSI I"],
      ["id"=>9,"name"=>"Wirtschaftsrecht IBO/OSI I"],
      ["id"=>10,"name"=>"Wirtschaftsenglisch IBO/OSI I"],
      ["id"=>11,"name"=>"Betriebliches Gesundheitsmanagement IBO/OSI I"]
    ]);
    }
}
