<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\TeacherNotAvailable;

class TeacherNotAvailableController extends Controller
{
    public function index()
    {
        //
    }
    public function teacher_create(Teacher $teacher)
    {
      return Inertia::render('Settings/TeacherNotAvailable/Create', [
          'teacher' => $teacher
      ]);
    }
    public function store(Request $request)
    {
      $request->validate([
      'teacher_id' => 'required',
      'awayDate' => 'required',

    ]);
      $away = new TeacherNotAvailable;
      $away -> teacher_id = $request->teacher_id; 
      $away -> awaystartdate = $request->awayDate[0]; 
      $away -> awayenddate = $request->awayDate[1]; 
      $away ->save();

      return redirect()->route('teacher.show',$request->teacher_id);
    }
    public function show(TeacherNotAvailable $teacherNotAvailable)
    {
        //
    }
    public function edit(TeacherNotAvailable $teacherNotAvailable)
    {
        //
    }
    public function update(Request $request, TeacherNotAvailable $teacherNotAvailable)
    {
        //
    }
    public function destroy(TeacherNotAvailable $teacherNotAvailable)
    {
        //
    }
}
