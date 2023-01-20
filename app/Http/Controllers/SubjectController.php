<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{

    public function index(Request $request)
    {
      return inertia('Settings/Subjects/Index',[
        'subjects' => Subject::query()
        ->when($request->search, function($query, $search) {
          $query->where('name' ,'like',"%$search%" );
        })
          ->paginate(5)
          ->withQueryString()
          ->through(fn($subject)=> [
            'id' => $subject->id,
            'name' => $subject->name,
            'color' => $subject->color,
          ]),
      'filters' => request()->only(['search'])
      ]);

    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Subject $subject)
    {
        //
    }

    public function edit(Subject $subject)
    {
        //
    }

    public function update(Request $request, Subject $subject)
    {
        //
    }


    public function destroy(Subject $subject)
    {
        //
    }
}
