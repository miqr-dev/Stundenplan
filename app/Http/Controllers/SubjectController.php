<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Subject;
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
      'templates' => 'array',
      'templates.*' => 'exists:templates,id'
    ]);

    $subject = Subject::create([
      'name' => $validated['name'],
      'color' => $validated['color'],
    ]);

    $subject->templates()->attach($validated['templates']);

    return redirect()->route('subject.index')->with('success', 'Subject created successfully.');
  }

  public function show($id)
  {
    $subject = Subject::with('templates')->findOrFail($id);
    return Inertia::render('Settings/Subjects/Show', [
      'subject' => $subject,
      'templates' => $subject->templates,
    ]);
  }

  public function edit(Subject $subject)
  {
    return Inertia::render('Settings/Subjects/Edit', [
      'subject' => [
        'id' => $subject->id,
        'name' => $subject->name,
        'color' => $subject->color,
        'templates' => $subject->templates->pluck('id')->toArray()
      ],
      'templates' => Template::all()
    ]);
  }

  public function update(Request $request, Subject $subject)
  {
    $data = $request->validate([
      'name' => 'required',
      'color' => 'required',
      'templates' => 'array',
      'templates.*' => 'exists:templates,id'
    ]);

    $subject->update([
      'name' => $data['name'],
      'color' => $data['color'],
    ]);

    $subject->templates()->sync($data['templates']);

    return redirect()->route('subject.index')->with('success', 'Subject updated successfully.');
  }

  public function destroy(Subject $subject)
  {
    $subject->delete();

    return redirect()->route('subject.index')->with('success', 'Subject deleted successfully.');
  }
}
