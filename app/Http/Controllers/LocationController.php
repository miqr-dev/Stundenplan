<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Location;
use App\Models\Rooms;
use Illuminate\Http\Request;

class LocationController extends Controller
{
  public function index()
  {
    return inertia('Settings/Locations/Index', [
      'cities' => City::with('locations')->get()
    ]);
  }

  public function create(City $city)
  {
    return inertia('Settings/Locations/Create', [
      'city' => $city
    ]);
  }

  public function store(Request $request)
  {
    $attributes = $request->validate([
      'city_id' => 'required',
      'name' => 'required|min:3',
    ]);
    Location::create($attributes);
    return redirect()->route('city.show',$request->city_id);
  }

  public function show(Location $location)
  {
    return inertia('Settings/Rooms/Show', [
      'location' => $location->load('rooms')
    ]);
  }

  public function edit(Location $location)
  {
    return inertia('Settings/Locations/Edit', [
      'location' => $location
    ]);
  }

  public function update(Request $request, Location $location)
  {
    $attributes = $request->validate([
      'name' => 'required|min:3',
    ]);

    $location->update($attributes);
    return redirect()->route('location.index');
  }


  public function destroy(Location $location)
  {
    $location->delete();
    return redirect()->route('location.index');
  }
}
