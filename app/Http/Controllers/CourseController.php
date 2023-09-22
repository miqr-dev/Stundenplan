<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Grid;
use App\Models\Room;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\Location;
use App\Models\Template;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    $templates = Template::with('subjects')->get();
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
      'subjects' => 'required|array',
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

    $subjects = [];
    foreach ($request->subjects as $subject) {
      $subjects[$subject['id']] = [
        'soll' => $subject['soll'], // Save the modified soll value
        'template_id' => $request->template_id,
      ];
    }
    $course->subjects()->attach($subjects);

    return redirect()->route('course.index')->with('success', 'Der Kurs wurde erfolgreich erstellt.');
  }

  public function show(Course $course)
  {
    $city_id = $course->room->location->city->id;

    $course = $course->load([
      'feriens',
      'template.subjects.teachers' => function ($query) use ($city_id) {
        $query->whereHas('cities', function ($query) use ($city_id) {
          $query->where('id', $city_id);
        });
      },
      'subjects.teachers' => function ($query) use ($city_id) {
        $query->whereHas('cities', function ($query) use ($city_id) {
          $query->where('id', $city_id);
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
    $subjects = $course->subjects;
       $course = $course->load(['subjects' => function ($query) use ($course) {
        $query->wherePivot('course_id', $course->id);
    }]);

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
      'subjects' => $subjects->map(function ($subject) {
        return [
          'id' => $subject->id,
          'name' => $subject->name,
          'soll_stunden' => $subject->soll_stunden,
        ];
      }),
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
        'subjects.*.id' => 'exists:subjects,id',
        'subjects.*.pivot.soll' => 'required|numeric',
    ]);

    // Handle course data
    $courseData = Arr::except($validated, ['subjects']);
    $course->update($courseData);

    // Handle subjects and soll_stunden
    $subjectsData = [];
    foreach ($validated['subjects'] as $subject) {
        $subjectsData[$subject['id']] = ['soll' => $subject['pivot']['soll']];
    }
    $course->subjects()->sync($subjectsData);

    return redirect()->route('course.index')->with('success', 'Der Kurs wurde erfolgreich aktualisiert.');
}

public function destroy(Course $course)
{
    $course->update(['grid_id' => null]);
    $course->delete();
    return redirect()->route('course.index')->with('success', 'Der Kurs wurde erfolgreich gelöscht.');
}
}
