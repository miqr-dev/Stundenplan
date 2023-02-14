<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\TeacherNotAvailable;

class TeacherNotAvailableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function teacher_create(Teacher $teacher)
    {
      return Inertia::render('Settings/TeacherNotAvailable/Create', [
          'teacher' => $teacher
      ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TeacherNotAvailable  $teacherNotAvailable
     * @return \Illuminate\Http\Response
     */
    public function show(TeacherNotAvailable $teacherNotAvailable)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TeacherNotAvailable  $teacherNotAvailable
     * @return \Illuminate\Http\Response
     */
    public function edit(TeacherNotAvailable $teacherNotAvailable)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TeacherNotAvailable  $teacherNotAvailable
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TeacherNotAvailable $teacherNotAvailable)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TeacherNotAvailable  $teacherNotAvailable
     * @return \Illuminate\Http\Response
     */
    public function destroy(TeacherNotAvailable $teacherNotAvailable)
    {
        //
    }
}
