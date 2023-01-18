<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
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
        'floor' => 'required|min:3',
        'capacity' => 'required|integer|between:1,99',
      ]);

      $room->update($attributes);
      return redirect()->route('location.index');
    }

    public function destroy(Room $room)
    {
        //
    }
}
