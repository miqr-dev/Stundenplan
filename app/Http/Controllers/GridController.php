<?php

namespace App\Http\Controllers;

use App\Models\Grid;
use App\Models\Gridslot;
use Illuminate\Http\Request;

class GridController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $grids = Grid::with('user')->get();

    return inertia('Settings/Grids/Index', [
      'grids' => $grids,
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return inertia('Settings/Grids/Create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $validatedData = $request->validate([
      'name' => 'required|max:255',
      'description' => 'nullable|max:65535',
    ]);

    auth()->user()->grids()->create($validatedData);

    return redirect()->route('grid.index')->with('success', 'The Grid has been created successfully');
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Grid  $grid
   * @return \Illuminate\Http\Response
   */
  public function show(Grid $grid)
  {
    $grid->load('user','gridslots');

    return inertia('Settings/Grids/Show', [
      'grid' => $grid,
    ]);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Grid  $grid
   * @return \Illuminate\Http\Response
   */
  public function edit(Grid $grid)
  {
    $gridslots = $grid->gridslots()->get();
    return inertia('Settings/Gridslots/Edit', [
      'grid' => $grid,
      'gridslots' => $gridslots
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Grid  $grid
   * @return \Illuminate\Http\Response
   */
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

    return redirect()->route('grid.index')->with('success', 'Grid is updated successfully');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Grid  $grid
   * @return \Illuminate\Http\Response
   */
  public function destroy(Grid $grid)
  {
    //
  }
}
