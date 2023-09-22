<?php

namespace App\Http\Controllers;

use App\Models\Grid;
use App\Models\Gridslot;
use Illuminate\Http\Request;

class GridController extends Controller
{
  public function index()
  {
    $grids = Grid::with('user')->get();

    return inertia('Settings/Grids/Index', [
      'grids' => $grids,
    ]);
  }
  public function create()
  {
    return inertia('Settings/Grids/Create');
  }
  public function store(Request $request)
  {
    $validatedData = $request->validate([
      'name' => 'required|max:255',
      'description' => 'nullable|max:65535',
    ]);

    auth()->user()->grids()->create($validatedData);

    return redirect()->route('grid.index')->with('success', 'Das Stundenraster wurde erfolgreich erstellt.');
  }
  public function show(Grid $grid)
  {
    $grid->load('user', 'gridslots');

    return inertia('Settings/Grids/Show', [
      'grid' => $grid,
    ]);
  }
  public function edit(Grid $grid)
  {
    $gridslots = $grid->gridslots()->get();
    return inertia('Settings/Grids/Edit', [
      'grid' => $grid,
    ]);
  }

  public function update(Request $request, Grid $grid)
  {
    $attributes = $request->validate([
      'name' => 'required|min:3',
      'description' => 'required|min:3',
    ]);

    $grid->update($attributes);

    return redirect()->route('grid.index')->with('success', 'Das Stundenraster wurde erfolgreich aktualisiert.');
  }
public function destroy(Grid $grid)
{
    if($grid->course) {
        return redirect()->route('grid.index')
            ->with('error', 'The Grid cannot be deleted, since it’s being used.');
    }

    $grid->delete();
    return redirect()->route('grid.index')
        ->with('success', 'Das Stundenraster wurde erfolgreich gelöscht.');
}
}
