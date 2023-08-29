<?php

namespace App\Http\Controllers;

use App\Models\City;
use Inertia\Inertia;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Template;
use Illuminate\Http\Request;

class SubjectController extends Controller
{

  public function index(Request $request)
  {
    return inertia('Settings/Subjects/Index', [
      'subjects' => Subject::query()
        ->when($request->search, function ($query, $search) {
          $query->where('name', 'like', "%$search%");
        })
        ->paginate(10)
        ->withQueryString()
        ->through(fn ($subject) => [
          'id' => $subject->id,
          'name' => $subject->name,
          'color' => $subject->color,
        ]),
      'filters' => request()->only(['search'])
    ]);
  }
  public function create()
  {
    $templates = Template::all();
    return Inertia::render('Settings/Subjects/Create', ['templates' => $templates]);
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required',
      'color' => 'required',
      'default_soll' => 'numeric',
      'templates' => 'array',
      'templates.*' => 'exists:templates,id'
    ]);

    $subject = Subject::create([
      'name' => $validated['name'],
      'color' => $validated['color'],
      'default_soll' => $validated['default_soll'],
    ]);

    $subject->templates()->attach($validated['templates']);

    return redirect()->route('subject.index')->with('success', 'Subject created successfully.');
  }

  // public function show($id)
  // {
  //   $subject = Subject::with('templates')->findOrFail($id);
  //   return Inertia::render('Settings/Subjects/Show', [
  //     'subject' => $subject,
  //     'templates' => $subject->templates,
  //   ]);
  // }

  public function show(Request $request, $id)
  {
    // Retrieve the subject and its related templates
    $subject = Subject::with('templates')->findOrFail($id);

    // Get the user's city
    $user = auth()->user();
    $city_id = $request->cityId ?? City::where('name', $user->ort)->value('id');

    // Retrieve the teachers related to this subject and located in the same city as the user
    $teachers = Teacher::whereHas('subjects', function ($query) use ($id) {
      $query->where('subject_id', $id);
    })->whereHas('cities', function ($query) use ($city_id) {
      $query->where('city_id', $city_id);
    })->get();

    return Inertia::render('Settings/Subjects/Show', [
      'subject' => $subject,
      'templates' => $subject->templates,
      'teachers' => $teachers,
    ]);
  }

  // public function edit(Subject $subject)
  // {
  //   return Inertia::render('Settings/Subjects/Edit', [
  //     'subject' => [
  //       'id' => $subject->id,
  //       'name' => $subject->name,
  //       'color' => $subject->color,
  //       'default_soll' => $subject->default_soll,
  //       'templates' => $subject->templates->pluck('id')->toArray()
  //     ],
  //     'templates' => Template::all()->map(function ($template) {
  //     return [
  //       'id' => $template->id,
  //       'name' => $template->name,
  //     ];
  //   }),
  //     'subject_template' => $subject->templates->map(function ($template) {
  //       return [
  //         'template_id' => $template->id,
  //         'soll' => $template->pivot->soll === null ? $template->default_soll : $template->pivot->soll
  //       ];
  //     }),
  //   ]);
  // }


  public function edit(Subject $subject, Request $request)
  {
    $user = auth()->user();
    $cityId = $request->cityId ?? City::where('name', $user->ort)->value('id');

    // Get teachers that are both associated with the subject and available in the user's city
    $filteredTeachers = $subject->teachers->filter(function ($teacher) use ($cityId) {
      return $teacher->cities->contains('id', $cityId);
    })->pluck('id')->toArray();

    $availableTeachers = Teacher::whereHas('cities', function ($query) use ($cityId) {
      $query->where('id', $cityId);
    })->get()->map(function ($teacher) {
      return [
        'id' => $teacher->id,
        'name' => $teacher->name . ', ' . $teacher->surname,
      ];
    });

    return Inertia::render('Settings/Subjects/Edit', [
      'subject' => [
        'id' => $subject->id,
        'name' => $subject->name,
        'color' => $subject->color,
        'default_soll' => $subject->default_soll,
        'templates' => $subject->templates->pluck('id')->toArray(),
        'teachers' => $filteredTeachers,  // Add the filtered teachers IDs
      ],
      'templates' => Template::all()->map(function ($template) {
        return [
          'id' => $template->id,
          'name' => $template->name,
        ];
      }),
      'subject_template' => $subject->templates->map(function ($template) {
        return [
          'template_id' => $template->id,
          'soll' => $template->pivot->soll === null ? $template->default_soll : $template->pivot->soll,
        ];
      }),
      'available_teachers' => $availableTeachers
    ]);
  }

  public function update(Request $request, Subject $subject)
  {
    $data = $request->validate([
      'name' => 'required',
      'color' => 'required',
      'default_soll' => 'numeric',
      'templates' => 'array',
      'templates.*' => 'exists:templates,id',
      'teachers' => 'array', 
      'teachers.*' => 'exists:teachers,id' 
    ]);

    $subject->update([
      'name' => $data['name'],
      'color' => $data['color'],
      'default_soll' => $data['default_soll'],
    ]);

    $subject->templates()->sync($data['templates']);
    $subject->teachers()->sync($data['teachers']);


    return redirect()->route('subject.index')->with('success', 'Subject updated successfully.');
  }

  public function destroy(Subject $subject)
  {
    $subject->delete();

    return redirect()->route('subject.index')->with('success', 'Subject deleted successfully.');
  }
}
