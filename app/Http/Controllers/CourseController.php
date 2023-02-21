<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\Template;
use Illuminate\Http\Request;

class CourseController extends Controller
{

  public function index(Request $request)
  {
    $courses = Course::with('city', 'template')->get();

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
    $templates = Template::all();
    $cities = City::all();

    return Inertia::render('Settings/Courses/Create', [
      'templates' => $templates,
      'cities' => $cities->map(function ($city) {
        return [
          'id' => $city->id,
          'name' => $city->name,
        ];
      })
    ]);
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required',
      'type' => 'required',
      'lbrn' => 'required',
      'city_id' => 'required|exists:cities,id',
      'template_id' => 'required|exists:templates,id',

    ]);
    Course::create($validated);

    return redirect()->route('course.index')->with('success', 'Course created successfully.');
  }

  public function show(Course $course)
  {
    // $course = $course->load(['template.subjects.teachers.cities']);
    // return Inertia::render('Settings/Courses/Show', [
    //   'course' => $course
    // ]);

    $course = $course->load(['template.subjects.teachers' => function ($query) use ($course) {
      $query->whereHas('cities', function ($query) use ($course) {
        $query->where('id', $course->city_id);
      });
    }]);

    return Inertia::render('Settings/Courses/Show', [
      'course' => $course
    ]);
  }

  public function edit(Course $course)
  {
    $templates = Template::all();
    $cities = City::all();

    return Inertia::render('Settings/Courses/Edit', [
      'course' => $course,
      'templates' => $templates,
      'cities' => $cities->map(function ($city) {
        return [
          'id' => $city->id,
          'name' => $city->name,
        ];
      })
    ]);
  }

  public function update(Request $request, Course $course)
  {
    $validated = $request->validate([
      'name' => 'required',
      'type' => 'required',
      'lbrn' => 'required',
      'city_id' => 'required|exists:cities,id',
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
