<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Location;
use Illuminate\Http\Request;

class RoomController extends Controller
{

  public function index()
  {
    //
  }

  public function create(Location $location)
  {
    return inertia('Settings/Rooms/Create', [
      'location' => $location
    ]);
  }

  public function store(Request $request)
  {
    $attributes = $request->validate([
      'location_id' => 'required',
      'name' => 'required|min:3',
      'room_number' => 'required|min:2',
      'floor' => 'required|min:2',
      'capacity' => 'required',
    ]);
    Room::create($attributes);
    return redirect()->route('location.show', $request->location_id);
  }

  public function show(Room $room)
  {
    //
  }

  public function edit(Room $room)
  {
    return inertia('Settings/Rooms/Edit', [
      'room' => $room->load('location')
    ]);
  }

  public function update(Request $request, Room $room)
  {
    $attributes = $request->validate([
      'name' => 'required|min:3',
      'room_number' => 'required|min:2',
      'floor' => 'required|min:2',
      'capacity' => 'required|integer|between:1,99',
    ]);

    $room->update($attributes);
    return redirect()->route('location.index');
  }

  public function destroy(Room $room)
  {
    $room->delete();
    return redirect()->route('location.index');
  }
}
