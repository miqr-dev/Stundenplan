<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Subject;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;

class TemplateController extends Controller
{
  public function index()
  {
    return Inertia::render('Settings/Templates/Index', [
      'templates' => Template::with('subjects')->get()
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
      'subjects' => 'required|array',
      'subjects.*' => 'exists:subjects,id'
    ]);

    $template = Template::create([
      'name' => $data['name'],
    ]);

    $template->subjects()->attach($data['subjects']);

    return redirect()->route('template.index')->with('success', 'Template created successfully.');
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
      'subjects' => Subject::all(),
    ]);
  }

  public function update(Request $request, Template $template)
  {
    $data = $request->validate([
      'name' => 'required',
      'subjects' => 'required|array',
      'subjects.*' => 'exists:subjects,id'
    ]);

    $template->update([
      'name' => $data['name'],
    ]);

    $template->subjects()->sync($data['subjects']);

    return redirect()->route('template.index')->with('success', 'Template created successfully.');
  }

  public function destroy(Template $template)
  {
    $template->delete();

    return redirect()->route('template.index')->with('success', 'Template deleted successfully.');
  }
}
