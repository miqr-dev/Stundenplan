<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Week;

class WeekSeeder extends Seeder
{
    public function run()
    {
        $start_year = 2022;
        $end_year = 2026;

        for ($year = $start_year; $year <= $end_year; $year++) {
            $start_of_year = Carbon::createFromDate($year, 1, 1);
            $first_monday = $start_of_year->next(Carbon::MONDAY);
            $monday = $first_monday;
            for ($week = 1; $week <= 52; $week++) {
                $friday = $monday->copy()->addDays(4);
                Week::create([
                    'WeekNumber' => $week,
                    'Year' => $year,
                    'StartDate' => $monday->toDateString(),
                    'EndDate' => $friday->toDateString(),
                ]);
                $monday->addWeek();
                if ($monday->year > $year) {
                    break;
                }
            }
        }
    }
}
