<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\City;
use App\Models\Room;
use App\Models\Week;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Conflict;
use App\Models\Stundenplan;
use Illuminate\Http\Request;
use App\Models\CourseSubject;
use App\Models\SchedualDetail;
use App\Models\SchedualMaster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class StundenplanController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */

  public function index()
  {
    $user = Auth::user();
    $city_id = City::where('name', $user->ort)->value('id');
    $courses = Course::whereHas('room.location', function ($query) use ($city_id) {
      $query->where('city_id', $city_id);
    })
      ->with([
        'grid.gridslots',
        'room.location.city',
        'subjects.teachers' => function ($query) use ($city_id) {
          $query->whereHas('cities', function ($query) use ($city_id) {
            $query->where('id', $city_id);
          });
        },
        'subjects.teachers.teacherNotAvailable'
      ])
      ->get();

    $rooms = Room::whereHas('location', function ($query) use ($city_id) {
      $query->where('city_id', $city_id);
    })->with('location')->get();

    $currentMonday = Carbon::now()->startOfWeek()->subWeeks(10);
    $endOfWeek = Carbon::now()->startOfWeek()->addWeek(10);

    $mondays = [];
    while ($currentMonday->lte($endOfWeek)) {
      $mondays[] = $currentMonday->toDateString();
      $currentMonday->addWeek();
    }
    $weekNumbers = Week::whereIn('startDate', $mondays)->get();

    return inertia('Stundenplan/Index', ['courses' => $courses, 'weekNumbers' => $weekNumbers, 'rooms' => $rooms,]);
  }


  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  public function teachingunit(Request $request)
  {
    $validated = $request->validate([
      'week' => 'required|integer',
      'year' => 'required|integer',
      'course_id' => 'required|integer',
      'grid_slot_id' => 'required|integer',
      'start_time' => 'required',
      'end_time' => 'required',
      'subject_id' => 'required',
      'teacher_id' => '',
      'room_id' => '',
      'template_id' => '',
      'date' => 'required|date',
    ]);
    // Check for existing master
    $existingMaster = SchedualMaster::where([
      'calendar_week' => $validated['week'],
      'year' => $validated['year'],
      'course_id' => $validated['course_id'],
      'date' => $validated['date'],
    ])->first();

    if ($existingMaster) {
      // Check for existing detail
      $existingDetail = SchedualDetail::where([
        'schedual_master_id' => $existingMaster->id,
        'start_time' => $validated['start_time'],
        'end_time' => $validated['end_time'],
      ])->first();

      if ($existingDetail) {

        $oldcourseSubject = DB::table('course_subject')
          ->where('course_id', $validated['course_id'])
          ->where('subject_id', $existingDetail->subject_id)
          ->where('template_id', $validated['template_id'])
          ->first();

        if ($oldcourseSubject) {
          // Add the duration of the teaching unit to 'ist'
          $startTime = new \DateTime($validated['start_time']);
          $endTime = new \DateTime($validated['end_time']);
          $duration = $endTime->diff($startTime)->h * 60 + $endTime->diff($startTime)->i; // duration in minutes
          $durationInHours = (int) ceil($duration / 60);
          // Update the ist value directly in the database
          DB::table('course_subject')
            ->where('course_id', $validated['course_id'])
            ->where('subject_id', $existingDetail->subject_id)
            ->where('template_id', $validated['template_id'])
            ->decrement('ist', $durationInHours);
        }

        $existingDetail->delete();
      }
    }

    $master = SchedualMaster::firstOrCreate([
      'calendar_week' => $validated['week'],
      'year' => $validated['year'],
      'course_id' => $validated['course_id'],
      'date' => $validated['date'],
    ]);

    $detail = SchedualDetail::create([
      'schedual_master_id' => $master->id,
      'grid_slot_id' => $validated['grid_slot_id'],
      'subject_id' => $validated['subject_id']['id'],
      'start_time' => $validated['start_time'],
      'end_time' => $validated['end_time'],
      'teacher_id' => $validated['teacher_id'] ? $validated['teacher_id']['id'] : null, // check if 'teacher_id' is not null before accessing 'id'
      'room_id' => $validated['room_id'],
    ]);

    // Update 'ist' field for the course, subject and template
    $courseSubject = DB::table('course_subject')
      ->where('course_id', $validated['course_id'])
      ->where('subject_id', $validated['subject_id']['id'])
      ->where('template_id', $validated['template_id'])
      ->first();

    if ($courseSubject) {
      // Add the duration of the teaching unit to 'ist'
      $startTime = new \DateTime($validated['start_time']);
      $endTime = new \DateTime($validated['end_time']);
      $duration = $endTime->diff($startTime)->h * 60 + $endTime->diff($startTime)->i; // duration in minutes
      $durationInHours = (int) ceil($duration / 60);
      // Update the ist value directly in the database
      DB::table('course_subject')
        ->where('course_id', $validated['course_id'])
        ->where('subject_id', $validated['subject_id']['id'])
        ->where('template_id', $validated['template_id'])
        ->increment('ist', $durationInHours);
    }
    return redirect()->back()->with('success', 'Unit saved successfully.');
  }

  public function checkTeachingUnit(Request $request)
  {
    $request->validate([
      'course_id' => 'required|integer',
      'week' => 'required|integer',
      'date' => 'required|date',
      'start_time' => 'required',
      'end_time' => 'required',
      'year' => 'required',
    ]);

    $master = SchedualMaster::where([
      'calendar_week' => $request->week,
      'course_id' => $request->course_id,
      'date' => $request->date,
      'year' => $request->year,
    ])->first();

    if (!$master) {
      return response()->json(['dataExists' => false]);
    }

    $detail = SchedualDetail::with('subject', 'teacher', 'room')->where([
      'schedual_master_id' => $master->id,
      'start_time' => $request->start_time,
      'end_time' => $request->end_time,
    ])->first();

    if ($detail === null) {
      return response()->json(['dataExists' => false]);
    } else {
      $detail->append('template_detail');
      $templateDetails = $detail->template_detail;

      $templateId = $templateDetails['template_id'];
      $courseId = $templateDetails['course_id'];

      $soll = DB::table('course_subject')
        ->where('course_id', $courseId)
        ->where('template_id', $templateId)
        ->get();

      $teacherId = $detail->teacher_id;
      $checkDate = $request->date;
      $startTime = $request->start_time;
      $endTime = $request->end_time;

      $overlappingSchedules = SchedualDetail::join('schedual_masters', 'schedual_masters.id', '=', 'schedual_details.schedual_master_id')
        ->where('schedual_details.teacher_id', $teacherId)
        ->where('schedual_masters.date', $checkDate)
        ->where(function ($query) use ($startTime, $endTime) {
          $query->where(function ($query) use ($startTime, $endTime) {
            $query->where('start_time', '<', $endTime)
              ->where('end_time', '>', $startTime);
          });
        })
        ->where('schedual_details.id', '!=', $detail->id) // Exclude the current SchedualDetail record from the query
        ->select('schedual_details.*') // To get only schedual_details fields
        ->with(['schedualMaster.course.city', 'subject', 'room']) // Including related data, adjust as necessary
        ->get();

      if ($overlappingSchedules->isEmpty()) {

        // Delete record from Conflict table if it exists
        Conflict::where([
          'user_id' => auth()->id(),
          'date' => $checkDate,
          'start_time' => $startTime,
          'end_time' => $endTime,
        ])->delete();

        return response()->json([
          'dataExists' => true,
          'detail' => $detail,
          'soll' => $soll
        ]);
      } else {
        // Overlapping schedules found
        foreach ($overlappingSchedules as $overlap) {
          Conflict::firstOrCreate(
            [
              'user_id' => auth()->id(),
              'teacher_id' => $teacherId,
              'first_course_id' => $detail->schedualMaster->course->id,
              'second_course_id' => $overlap->schedualMaster->course->id,
              'date' => $checkDate,
              'start_time' => $startTime,
              'end_time' => $endTime,
            ],
          );
        }
        return response()->json([
          'dataExists' => true,
          'detail' => $detail,
          'soll' => $soll,
          'conflict' => true,
          'overlappingSchedules' => $overlappingSchedules
        ]);
      }
    }
  }

  public function checkTeacherConflicts(Request $request)
  {
    ddd($request);
    $date = $request->input('date');
    $startTime = $request->input('start_time');
    $endTime = $request->input('end_time');
    $subjectId = $request->input('subject_id');

    // Find all teachers related to the subject
    $subjectTeachers = Teacher::whereHas('subjects', function ($query) use ($subjectId) {
      $query->where('subject_id', $subjectId);
    })->pluck('id')->toArray(); // pluck 'id' to get only the teacher IDs in an array

    // Find all teacher IDs that have conflicts
    $conflictedSchedules = SchedualDetail::where([
      ['date', $date],
      ['subject_id', $subjectId],
    ])
      ->whereIn('teacher_id', $subjectTeachers)
      ->where(function ($query) use ($startTime, $endTime) {
        $query->whereBetween('start_time', [$startTime, $endTime])
          ->orWhereBetween('end_time', [$startTime, $endTime]);
      })
      ->select('teacher_id', 'start_time', 'end_time', 'course_id', 'schedual_master_id')
      ->get()->toArray();

    return response()->json([
      'conflicts' => $conflictedSchedules
    ]);
  }





  public function store(Request $request)
  {
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Stundenplan  $stundenplan
   * @return \Illuminate\Http\Response
   */
  public function show(Stundenplan $stundenplan)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Stundenplan  $stundenplan
   * @return \Illuminate\Http\Response
   */
  public function edit(Stundenplan $stundenplan)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Stundenplan  $stundenplan
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Stundenplan $stundenplan)
  {
    //
  }




  public function updateCity($cityId)
  {
    $city = City::findOrFail($cityId);
    $courses = $city->courses;

    return Inertia::render('Stundenplan/Index', [
      'cities' => City::all(),
      'selectedCityId' => $city->id, // Add id here
      'selectedCityLocations' => $city->locations,
      'cityName' => $city->name,
      'courses' => $courses
    ]);
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Stundenplan  $stundenplan
   * @return \Illuminate\Http\Response
   */
  public function destroy(Stundenplan $stundenplan)
  {
    //
  }
}
