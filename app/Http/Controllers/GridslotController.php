<?php

namespace App\Http\Controllers;

use App\Models\Grid;
use Inertia\Inertia;
use App\Models\Gridslot;
use Illuminate\Http\Request;

class GridslotController extends Controller
{
  public function index()
  {
  }
  public function create(Grid $grid)
  {
    return inertia('Settings/Gridslots/Create', [
      'grid' => $grid
    ]);
  }
  public function store(Request $request)
  {
    $validatedData = $request->validate([
      'grid_id' => ['required', 'exists:grids,id'],
      'start_times.*' => ['required', 'date_format:H:i'],
      'end_times.*' => ['required', 'date_format:H:i'],
      'breaks.*.is_break' => ['required', 'boolean'],
    ]);

    // Check if any new grid slot overlaps with an existing one
    $gridId = $validatedData['grid_id'];
    $startTimes = $validatedData['start_times'];
    $endTimes = $validatedData['end_times'];
    foreach ($startTimes as $index => $startTime) {
      $endTime = $endTimes[$index];
      $overlappingSlots = Gridslot::where('grid_id', $gridId)
        ->where(function ($query) use ($startTime, $endTime) {
          $query->whereBetween('start_time', [$startTime, $endTime])
            ->orWhere(function ($query) use ($startTime, $endTime) {
              $query->where('start_time', '<', $startTime)
                ->where('end_time', '>', $startTime);
            })
            ->orWhere(function ($query) use ($startTime, $endTime) {
              $query->where('start_time', '<=', $endTime)
                ->where('end_time', '>=', $endTime);
            });
        })
        ->get();

      if ($overlappingSlots->count() > 0) {
        $request->session()->flash('error', 'Das neue Zeitfenster überschneidet sich mit einem bereits existierenden.');
        return redirect()->back();
      }
    }

    // Create the new grid slots
    foreach ($startTimes as $index => $startTime) {
      $endTime = $endTimes[$index];
      $isBreak = $validatedData['breaks'][$index]['is_break'];

      $gridSlot = new Gridslot();
      $gridSlot->grid_id = $gridId;
      $gridSlot->start_time = $startTime;
      $gridSlot->end_time = $endTime;
      $gridSlot->is_break = $isBreak;
      $gridSlot->save();
    }

    $request->session()->flash('message', 'Das/Die Zeitfenster wurde erfolgreich erstellt.');
    return redirect()->route('grid.index');
  }
  public function show(Gridslot $gridslot)
  {
    //
  }
public function edit(Grid $grid) {
    $gridslots = $grid->gridslots()->get();
    return inertia('Settings/Gridslots/Edit', [
        'grid' => $grid,
        'gridslots' => $gridslots
    ]);
}

  public function update(Request $request, Grid $grid)
{
    $validatedData = $request->validate([
        'start_times' => ['array', 'required'],
        'end_times' => ['array', 'required'],
        'is_breaks' => ['array', 'required'],
        'is_breaks.*' => ['boolean'],
    ]);

    $gridslots = $grid->gridslots;

    foreach ($gridslots as $index => $gridslot) {
        $gridslot->update([
            'start_time' => $validatedData['start_times'][$index],
            'end_time' => $validatedData['end_times'][$index],
            'is_break' => $validatedData['is_breaks'][$index],
        ]);
    }

    return redirect()->route('grid.index')->with('success','Das Zeitfenster wurde erfolgreich aktualisiert.');
}
  public function destroy(Gridslot $gridslot)
  {
    //
  }
}
