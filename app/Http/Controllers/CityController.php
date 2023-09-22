<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Bundesland;
use Illuminate\Http\Request;

class CityController extends Controller
{

  public function index()
  {
    return inertia('Settings/Cities/Index', [
      'cities' => City::all()->map(function ($city) {
        return [
          'id' => $city->id,
          'name' => $city->name
        ];
      })
    ]);
  }

  public function create()
  {
    $bundesland = Bundesland::all();
    return inertia('Settings/Cities/Create', [
      'bundesland' => $bundesland
    ]);
  }

  public function store(Request $request)
  {
    $attributes = $request->validate([
      'name' => 'required|min:3',
      'bundesland_id' => 'required',
    ]);

    City::create($attributes);

    return redirect()->route('city.index')->with('success', 'Die Stadt wurde erfolgreich erstellt.');;
  }

  public function show(City $city)
  {
    return inertia('Settings/Locations/Show', [
      'city' => $city->load('locations')
    ]);
  }
  public function edit(City $city)
  {
    $bundesland = Bundesland::all();
    return inertia('Settings/Cities/Edit', [
      'city' => $city,
      'bundesland' => $bundesland
    ]);
  }
  public function update(Request $request, City $city)
  {
    $attributes = $request->validate([
      'name' => 'required|min:3',
      'bundesland_id' => 'required|exists:bundeslands,id',
    ]);

    $city->update($attributes);

    return redirect()->route('city.index')->with('success','Die Stadt wurde erfolgreich aktualisiert.');
  }
  public function destroy(City $city)
  {
    $city->delete();
    return redirect()->route('city.index')->with('success', 'Die Stadt wurde erfolgereich gelöscht.');
  }
}
