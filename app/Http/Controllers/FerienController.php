<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Course;
use App\Models\Ferien;
use Illuminate\Http\Request;

class FerienController extends Controller
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

  public function create(Request $request)
  {
    $course_id = $request->input('course'); // Get the 'course' attribute from the request payload
    $course = Course::findOrFail($course_id); // Find the course or fail

    return Inertia::render('Settings/Ferien/Create', [
      'course' => $course,
    ]);
  }

    public function store(Request $request)
    {
      $request->validate([
      'course_id' => 'required',
      'ferienDate' => 'required',
      'name' => 'required',

    ]);
      $away = new Ferien;
      $away -> name = $request->name; 
      $away -> course_id = $request->course_id; 
      $away -> start_date = $request->ferienDate[0]; 
      $away -> end_date = $request->ferienDate[1]; 
      $away ->save();

      return redirect()->route('course.show',$request->course_id);
    }
  public function show($id)
  {
    //
  }
  public function edit($id)
  {
    //
  }
  public function update(Request $request, $id)
  {
    //
  }
  public function destroy($id)
  {
    //
  }
}
