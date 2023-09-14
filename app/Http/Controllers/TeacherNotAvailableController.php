<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\SchedualDetail;
use App\Models\TeacherNotAvailable;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AffectedSchedulesNotification;
use Illuminate\Support\Facades\Auth;

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

    // Save the TeacherNotAvailable record
    $away = new TeacherNotAvailable;
    $away->teacher_id = $request->teacher_id;
    $away->awaystartdate = $request->awayDate[0];
    $away->awayenddate = $request->awayDate[1];
    $away->save();

    // Find and update the SchedualDetails that include the teacher during the unavailable period
    $startDateTime = date('Y-m-d 00:00:00', strtotime($request->awayDate[0]));
    $endDateTime = date('Y-m-d 23:59:59', strtotime($request->awayDate[1]));

    $affectedSchedules = SchedualDetail::join('schedual_masters', 'schedual_details.schedual_master_id', '=', 'schedual_masters.id')
      ->where('schedual_details.teacher_id', $request->teacher_id)
      ->whereBetween('schedual_masters.date', [$startDateTime, $endDateTime])
      ->select('schedual_details.*') // only select columns from schedual_details
      ->get();

    $affectedSchedulesWithRelations = SchedualDetail::with([
      'schedualMaster',
      'gridSlot',
      'subject',
      'teacher',
      'room',
      'schedualMaster.course' // Assuming you have a course relation in your SchedualMaster model
    ])
      ->whereIn('id', $affectedSchedules->pluck('id'))
      ->get();
    foreach ($affectedSchedulesWithRelations as $affectedSchedule) {
      Notification::send(Auth::user(), new AffectedSchedulesNotification($affectedSchedule));
    }

    foreach ($affectedSchedules as $schedualDetail) {
      $schedualDetail->teacher_id = null;
      $schedualDetail->save();
    }

    return redirect()->route('teacher.show', $request->teacher_id);
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
