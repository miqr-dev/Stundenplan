<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Grid;
use App\Models\Room;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\Location;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{

  public function index(Request $request)
  {
    return Inertia::render('Settings/Courses/Index', [
      'courses' => Course::query()
        ->with(['city', 'template'])
        ->when($request->search, function ($query, $search) {
          $query->where('name', 'like', "%$search%");
        })
        ->paginate(5)
        ->withQueryString()
        ->through(fn ($course) => [
          'id' => $course->id,
          'name' => $course->name,
          'type' => $course->type,
          'lbrn' => $course->lbrn,
        ]),
      'filters' => request()->only(['search'])
    ]);
  }

  public function create()
  {
    $user = Auth::user();
    $city_id = City::where('name', $user->ort)->value('id');
    $templates = Template::all();
    $grids = Grid::all();

    // Fetch locations in the given city
    $locations = City::where('id', $city_id)->with('locations')->first()->locations;

    // Fetch rooms with their related locations in the given city
    $rooms = Room::with('location')->whereIn('location_id', $locations->pluck('id'))->get();

    return Inertia::render('Settings/Courses/Create', [
      'templates' => $templates,
      'grids' => $grids,
      'locations' => $locations,
      'rooms' => $rooms,
    ]);
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required',
      'type' => 'required',
      'color' => 'required',
      'grid_id' => 'required',
      'courseDate' => 'required',
      'lbrn' => 'required',
      'room_id' => 'required|exists:rooms,id',
      'template_id' => 'required|exists:templates,id',

    ]);

    $course = new Course;
    $course->name = $request->name;
    $course->type = $request->type;
    $course->color = $request->color;
    $course->start_date = $request->courseDate[0];
    $course->end_date = $request->courseDate[1];
    $course->lbrn = $request->lbrn;
    $course->room_id = $request->room_id;
    $course->grid_id = $request->grid_id;
    $course->template_id = $request->template_id;
    $course->save();

    return redirect()->route('course.index')->with('success', 'Course created successfully.');
  }

  public function show(Course $course)
  {
    $course = $course->load([
      'template.subjects.teachers' => function ($query) use ($course) {
        $query->whereHas('cities', function ($query) use ($course) {
          $query->where('id', $course->room->location->city->id);
        });
      }
    ]);

    return Inertia::render('Settings/Courses/Show', [
      'course' => $course
    ]);
  }

  public function edit(Course $course)
  {
    $user = Auth::user();
    $city_id = City::where('name', $user->ort)->value('id');
    $templates = Template::all();
    $locations = City::where('id', $city_id)->with('locations')->first()->locations;
    // Fetch rooms with their related locations in the given city
    $rooms = Room::with('location')->whereIn('location_id', $locations->pluck('id'))->get();

    return Inertia::render('Settings/Courses/Edit', [
      'course' => $course,
      'templates' => $templates,
      'locations' => $locations->map(function ($location) {
        return [
          'id' => $location->id,
          'name' => $location->name,
        ];
      }),
      'rooms' => $rooms,
    ]);
  }

  public function update(Request $request, Course $course)
  {
    $validated = $request->validate([
      'name' => 'required',
      'type' => 'required',
      'lbrn' => 'required',
      'room_id' => '',
      'template_id' => 'required|exists:templates,id',
    ]);

    $course->update($validated);

    return redirect()->route('course.index')->with('success', 'Course updated successfully.');
  }

  public function destroy(Course $course)
  {
    $course->delete();
    return redirect()->route('course.index')->with('success', 'Course deleted successfully.');
  }
}
