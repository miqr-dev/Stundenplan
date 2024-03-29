<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Subject;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;

class TemplateController extends Controller
{
public function index(Request $request)
    {
        return Inertia::render('Settings/Templates/Index', [
            'templates' => Template::query()
                ->with('subjects')
                ->when($request->search, function ($query, $search) {
                    $query->where('name', 'like', "%$search%");
                })
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($template) => [
                    'id' => $template->id,
                    'name' => $template->name,
                    'subjects' => $template->subjects->map(function ($subject) {
                        return [
                            'id' => $subject->id,
                            'name' => $subject->name,
                            'color' => $subject->color,
                        ];
                    }),
                ]),
            'filters' => request()->only(['search']),
        ]);
    }

  public function create()
  {
    $subjects = Subject::all();
    return Inertia::render('Settings/Templates/Create', ['subjects' => $subjects]);
  }

public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required',
        'subjects' => 'array',
        'subjects.*' => 'exists:subjects,id'
    ]);

    $template = Template::create([
        'name' => $data['name'],
    ]);

    // Loop through the subjects and attach them with their default_soll values
    foreach ($data['subjects'] as $subjectId) {
        $subject = Subject::find($subjectId);
        $template->subjects()->attach($subject->id, ['soll' => $subject->default_soll]);
    }

    return redirect()->route('template.index')->with('success', 'Die Vorlage wurde erfolgreich erstellt.');
}


    public function show($id)
  {
    $template = Template::with('subjects')->findOrFail($id);
    return Inertia::render('Settings/Templates/Show', [
      'template' => $template,
    ]);
  }

public function edit(Template $template)
{
  return Inertia::render('Settings/Templates/Edit', [
    'template' => [
      'id' => $template->id,
      'name' => $template->name,
      'subjects' => $template->subjects->pluck('id')->toArray()
    ],
    'subjects' => Subject::all()->map(function ($subject) {
      return [
        'id' => $subject->id,
        'name' => $subject->name,
        'default_soll' => $subject->default_soll,
      ];
    }),
  ]);
}

public function update(Request $request, Template $template)
{
    $data = $request->validate([
        'name' => 'required',
        'subjects' => 'required|array',
        'subjects.*' => 'exists:subjects,id',
    ]);

    $template->update([
        'name' => $data['name'],
    ]);

    $template->subjects()->sync($data['subjects']);

    return redirect()->route('template.index')->with('success', 'Die Vorlage wurde erfolgreich aktualisiert.');
}

  public function destroy(Template $template)
  {
    $template->delete();

    return redirect()->route('template.index')->with('success', 'Die Vorlage wurde erfolgreich gelöscht.');
  }
}
