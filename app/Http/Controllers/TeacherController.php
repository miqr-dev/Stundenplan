<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherNotAvailable;
use Illuminate\Http\Request;

class TeacherController extends Controller
{

  public function index(Request $request)
  {
    return Inertia::render('Settings/Teacher/Index', [
      'teachers' => Teacher::query()
        ->with(['teacherNotAvailable', 'cities', 'subjects'])
        ->when($request->search, function ($query, $search) {
          $query->where('name', 'like', "%$search%")
            ->orWhere('surname', 'like', "%$search%");
        })
        ->paginate(5)
        ->withQueryString()
        ->through(fn ($teacher) => [
          'id' => $teacher->id,
          'name' => $teacher->name,
          'surname' => $teacher->surname,
          'color' => $teacher->color,
        ]),
      'filters' => request()->only(['search'])
    ]);
  }

  public function create()
  {
    $subjects = Subject::all();
    $cities = City::all();
    return Inertia::render('Settings/Teacher/Create', [
      'subjects' => $subjects,
      'cities' => $cities
    ]);
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required',
      'surname' => 'required',
      'maxhourweek' => 'required',
      'color' => 'required',
      'email' => 'required|email|unique:teachers',
      'cities' => 'required|array|min:1',
      'cities.*' => 'required|exists:cities,id',
      'subjects' => 'required|array|min:1',
      'subjects.*' => 'required|exists:subjects,id',
    ]);
    $teacher = Teacher::create([
      'name' => $validated['name'],
      'surname' => $validated['surname'],
      'maxhourweek' => $validated['maxhourweek'],
      'color' => $validated['color'],
      'email' => $validated['email'],
    ]);
    $teacher->cities()->attach($validated['cities']);
    $teacher->subjects()->attach($validated['subjects']);

    return redirect()->route('teacher.index')->with('success', 'Teacher created successfully.');
  }

  public function show(Teacher $teacher)
  {
    $teacher->load(['subjects', 'cities', 'teacherNotAvailable']);

    return Inertia::render('Settings/Teacher/Show', [
      'teacher' => $teacher
    ]);
  }

  public function edit(Teacher $teacher)
  {
    // $teacher->load(['subjects', 'cities', 'teacherNotAvailable']);

    return Inertia::render('Settings/Teacher/Edit', [
      'teacher' => [
        'id' => $teacher->id,
        'name' => $teacher->name,
        'surname' => $teacher->surname,
        'maxhourweek' => $teacher->maxhourweek,
        'email' => $teacher->email,
        'color' => $teacher->color,
        'teacherNotAvailable' => $teacher->teacherNotAvailable->pluck('id')->toArray(),
        'subjects' => $teacher->subjects->pluck('id')->toArray(),
        'cities' => $teacher->cities->pluck('id')->toArray()
      ],
      'cities' => City::all(),
      'subjects' => Subject::all(),
      'teacherNotAvailable' => TeacherNotAvailable::all()
    ]);
  }


  public function update(Request $request, Teacher $teacher)
  {
      $data = $request->validate([
      'name' => 'required',
      'surname' => 'required',
      'maxhourweek' => 'required',
      'color' => 'required',
      'email' => 'required|email',
      'cities' => 'required|array|min:1',
      'cities.*' => 'required|exists:cities,id',
      'subjects' => 'required|array|min:1',
      'subjects.*' => 'required|exists:subjects,id',
    ]);
      $teacher->update([
      'name' => $data['name'],
      'surname' => $data['surname'],
      'maxhourweek' => $data['maxhourweek'],
      'color' => $data['color'],
      'email' => $data['email'],
    ]);

    $teacher->cities()->sync($data['cities']);
    $teacher->subjects()->sync($data['subjects']);


    return redirect()->route('teacher.index')->with('success', 'Teacher updated successfully.');
  }

  public function destroy($id)
  {
    $teacher = Teacher::with(['teacherNotAvailable', 'cities', 'subjects'])->findOrFail($id);
    $teacher->delete();
    return redirect()->route('teacher.index')->with('success', 'Teacher deleted successfully.');
  }
}
