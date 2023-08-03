<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\City;
use App\Models\Room;
use App\Models\Week;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\Stundenplan;
use Illuminate\Http\Request;
use App\Models\SchedualDetail;
use App\Models\SchedualMaster;
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
    $user = Auth::user();
    $city_id = City::where('name', $user->ort)->value('id');
    $courses = Course::whereHas('room.location', function ($query) use ($city_id) {
      $query->where('city_id', $city_id);
    })->with(['grid.gridslots', 'room.location.city', 'template.subjects.teachers.teacherNotAvailable'])->get();
    $rooms = Room::whereHas('location', function ($query) use ($city_id) {
      $query->where('city_id', $city_id);
    })->with('location')->get();


    $currentMonday = Carbon::now()->startOfWeek()->subWeeks(10);
    $endOfWeek = Carbon::now()->startOfWeek()->addWeek(10);

    $mondays = [];
    while ($currentMonday->lte($endOfWeek)) {
      $mondays[] = $currentMonday->toDateString();
      $currentMonday->addWeek();
    }
    $weekNumbers = Week::whereIn('startDate', $mondays)->get();
    return inertia('Stundenplan/Index', ['courses' => $courses, 'weekNumbers' => $weekNumbers, 'rooms' => $rooms,]);
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

  public function teachingunit(Request $request)
  {
    $validated = $request->validate([
      'week' => 'required|integer',
      'course_id' => 'required|integer',
      'grid_slot_id' => 'required|integer',
      'start_time' => 'required',
      'end_time' => 'required',
      'subject_id' => 'required',
      'teacher_id' => '',
      'room_id' => '',
      'date' => 'required|date',
    ]);

    // Check for existing master
    $existingMaster = SchedualMaster::where([
      'calendar_week' => $validated['week'],
      'course_id' => $validated['course_id'],
      'date' => $validated['date'],
    ])->first();

    if ($existingMaster) {
      // Check for existing detail
      $existingDetail = SchedualDetail::where([
        'schedual_master_id' => $existingMaster->id,
        'start_time' => $validated['start_time'],
        'end_time' => $validated['end_time'],
      ])->first();

      if ($existingDetail) {
        // Delete the detail
        $existingDetail->delete();
      }
    }

    $master = SchedualMaster::firstOrCreate([
      'calendar_week' => $validated['week'],
      'course_id' => $validated['course_id'],
      'date' => $validated['date'],
    ]);

    $detail = SchedualDetail::create([
      'schedual_master_id' => $master->id,
      'grid_slot_id' => $validated['grid_slot_id'],
      'subject_id' => $validated['subject_id']['id'],
      'start_time' => $validated['start_time'],
      'end_time' => $validated['end_time'],
      'teacher_id' => $validated['teacher_id'] ? $validated['teacher_id']['id'] : null, // check if 'teacher_id' is not null before accessing 'id'
      'room_id' => $validated['room_id'],
    ]);

    return redirect()->back()->with('success', 'Unit saved successfully.');
  }

  public function checkTeachingUnit(Request $request)
  {
    $request->validate([
      'course_id' => 'required|integer',
      'week' => 'required|integer',
      'date' => 'required|date',
      'start_time' => 'required',
      'end_time' => 'required',
    ]);

    $master = SchedualMaster::where([
      'calendar_week' => $request->week,
      'course_id' => $request->course_id,
      'date' => $request->date,
    ])->first();

    if (!$master) {
      return response()->json(['dataExists' => false]);
    }

    $detail = SchedualDetail::with('subject', 'teacher', 'room')->where([
      'schedual_master_id' => $master->id,
      'start_time' => $request->start_time,
      'end_time' => $request->end_time,
    ])->first();

    if ($detail === null) {
      return response()->json(['dataExists' => false]);
    } else {
      return response()->json(['dataExists' => true, 'detail' => $detail]);
    }
  }

  public function store(Request $request)
  {
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
