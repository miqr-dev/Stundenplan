<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use App\Models\Feiertag;
use App\Models\Bundesland;
use Illuminate\Console\Command;

class ImportHolidays extends Command
{
  protected $signature = 'import:holidays';
  protected $description = 'Import holidays for Thüringen, Sachsen, and Berlin for the next 4th year.';
  public function __construct()
  {
    parent::__construct();
  }
  public function handle()
  {
    $client = new Client();
    $bundeslaender = ['TH', 'SN', 'BE'];

    for ($yearOffset = 0; $yearOffset <= 3; $yearOffset++) {
      $year = now()->year + $yearOffset;

      foreach ($bundeslaender as $short_name) {
        $response = $client->get("http://feiertage-api.de/api/?jahr={$year}&nur_land={$short_name}&nur_daten");
        $data = json_decode($response->getBody(), true);

        $bundesland = Bundesland::where('short_name', $short_name)->first();

        if (!$bundesland) {
          $this->error("Bundesland with short name {$short_name} not found.");
          continue;
        }

        foreach ($data as $holidayName => $holidayData) {
          if ($holidayName === 'Fronleichnam') {
            continue;
          }

          // Assign the date directly
          $date = $holidayData;

          Feiertag::updateOrCreate(
            ['name' => $holidayName, 'bundesland_id' => $bundesland->id, 'year' => $year],
            ['date' => $date]
          );
        }
      }

      $this->info("Holidays for {$year} imported successfully.");
    }
  }
}
