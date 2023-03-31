<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\City;
use App\Models\Week;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\Stundenplan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StundenplanController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */

public function index()
{
    $user = Auth::user(); //todo polor applicationin mech asi bedke asang ashkhadi
    $city_id = City::where('name', $user->ort)->value('id');
    //$cities = City::with('locations')->get();
    $courses = Course::where('city_id', $city_id)->with('grid.gridslots')->with('city.locations')->with('template.subjects.teachers')->get();
    //dd($courses);


     // Get the current week number and the last week number of the year
    $currentMonday = Carbon::now()->startOfWeek(); //todo the day from today until end of the year
    $endOfYear = Carbon::now()->endOfYear();

    // Generate an array of Mondays from the current week to the end of the year
    $mondays = [];
    while ($currentMonday->lte($endOfYear)) {
        $mondays[] = $currentMonday->toDateString();
        $currentMonday->addWeek();
    }
     $weekNumbers = Week::whereIn('startDate', $mondays)->get();

    //$weekNumbers = Week::all(); 

    return inertia('Stundenplan/Index', [
        'courses' => $courses,
        'weekNumbers' =>  $weekNumbers
    ]);
}

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Stundenplan  $stundenplan
   * @return \Illuminate\Http\Response
   */
  public function show(Stundenplan $stundenplan)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Stundenplan  $stundenplan
   * @return \Illuminate\Http\Response
   */
  public function edit(Stundenplan $stundenplan)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Stundenplan  $stundenplan
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Stundenplan $stundenplan)
  {
    //
  }

  


public function updateCity($cityId)
{
    $city = City::findOrFail($cityId);
    $courses = $city->courses;
    
    return Inertia::render('Stundenplan/Index', [
        'cities' => City::all(),
         'selectedCityId' => $city->id, // Add id here
        'selectedCityLocations' => $city->locations,
        'cityName' => $city->name,
        'courses' => $courses
    ]);
}


  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Stundenplan  $stundenplan
   * @return \Illuminate\Http\Response
   */
  public function destroy(Stundenplan $stundenplan)
  {
    //
  }
}
