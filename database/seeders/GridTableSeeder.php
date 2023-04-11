<?php

namespace Database\Seeders;
use Carbon\Carbon;
use App\Models\Grid;
use App\Models\Gridslot;
use Illuminate\Database\Seeder;

class GridTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       // create Erfurt Default grid
        $erfurtDefault = Grid::create([
            'name' => 'Erfurt Default',
            'description' => 'Default Erfurt Grid from 8:00 to 15:00',
            'user_id' => 1, 
        ]);
        
        // create grid slots for Erfurt Default grid
        Gridslot::create([
            'grid_id' => $erfurtDefault->id,
            'start_time' => Carbon::parse('8:00'),
            'end_time' => Carbon::parse('9:30'),
            'is_break' => true,
        ]);
        Gridslot::create([
            'grid_id' => $erfurtDefault->id,
            'start_time' => Carbon::parse('9:40'),
            'end_time' => Carbon::parse('11:10'),
            'is_break' => true,
        ]);
        Gridslot::create([
            'grid_id' => $erfurtDefault->id,
            'start_time' => Carbon::parse('11:15'),
            'end_time' => Carbon::parse('12:00'),
            'is_break' => true,
        ]);
        Gridslot::create([
            'grid_id' => $erfurtDefault->id,
            'start_time' => Carbon::parse('12:40'),
            'end_time' => Carbon::parse('14:10'),
            'is_break' => true,
        ]);
        Gridslot::create([
            'grid_id' => $erfurtDefault->id,
            'start_time' => Carbon::parse('14:15'),
            'end_time' => Carbon::parse('15:00'),
            'is_break' => false,
        ]);

        // create Suhl Teilzeit grid
        $suhlTeilzeit = Grid::create([
            'name' => 'Suhl Teilzeit',
            'description' => 'Suhl grid from 8:00 to 12:00',
            'user_id' => 1, // replace with the user ID
        ]);
         // create grid slots for Suhl Teilzeit grid
           Gridslot::create([
        'grid_id' => $suhlTeilzeit->id,
        'start_time' => Carbon::parse('8:00'),
        'end_time' => Carbon::parse('9:30'),
        'is_break' => true,
    ]);
    Gridslot::create([
        'grid_id' => $suhlTeilzeit->id,
        'start_time' => Carbon::parse('9:40'),
        'end_time' => Carbon::parse('11:10'),
        'is_break' => true,
    ]);
    Gridslot::create([
        'grid_id' => $suhlTeilzeit->id,
        'start_time' => Carbon::parse('11:15'),
        'end_time' => Carbon::parse('12:00'),
        'is_break' => true,
    ]);        
    }
}
