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
         return inertia('Settings/Locations/Index',[
          'locations' => City::with('locations')->get()
        ]);
    }

    public function create()
    {
        return inertia('Settings/Locations/Create');
    }

    public function store(Request $request)
    {
        return $request;
        $attributes = $request->validate([
        'name' => 'required|min:3',
      ]);
      Location::create($attributes);
    }

    public function show(Location $location)
    {
        return inertia('Settings/Rooms/Show',[
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
