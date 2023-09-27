<?php

namespace App\Http\Controllers;

use DateTime;
use DatePeriod;
use DateInterval;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\Praktikum;
use Illuminate\Http\Request;
use App\Models\SchedualDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AffectedSchedulesNotification;

class PraktikumController extends Controller
{

  public function index()
  {
  }

  public function create(Request $request)
  {
    $course_id = $request->input('course');
    $course = Course::findOrFail($course_id);

    return Inertia::render('Settings/Praktikum/Create', [
      'course' => $course,
    ]);
  }

public function store(Request $request)
{
    $request->validate([
        'course_id' => 'required|exists:courses,id',
        'name' => 'required|string|max:255',
        'dateRange' => 'required|array|size:2',
        'dateRange.0' => 'required|date|before:dateRange.1',
        'dateRange.1' => 'required|date|after:dateRange.0',
        'repeat_days' => 'required|array|min:1',
        'repeat_days.*' => 'required|string|in:Montag,Dienstag,Mittwoch,Donnerstag,Freitag',
    ]);

    $germanToEnglishDays = [
        'Montag' => 'Monday',
        'Dienstag' => 'Tuesday',
        'Mittwoch' => 'Wednesday',
        'Donnerstag' => 'Thursday',
        'Freitag' => 'Friday',
    ];

    $start = new DateTime($request->dateRange[0]);
    $end = new DateTime($request->dateRange[1]);
    $interval = new DateInterval('P1D');
    $daterange = new DatePeriod($start, $interval, $end->modify('+1 day'));

    $dates = [];
    foreach ($daterange as $date) {
        $day = $date->format('l');
        $dayInGerman = array_search($day, $germanToEnglishDays);
        if (in_array($dayInGerman, $request->repeat_days)) {
            $dates[] = $date->format('Y-m-d');
        }
    }

    if (!empty($dates)) {
        $praktikum = new Praktikum;
        $praktikum->name = $request->name;
        $praktikum->course_id = $request->course_id;
        $praktikum->start_date = $request->dateRange[0];
        $praktikum->end_date = $request->dateRange[1];
        $praktikum->repeat_days = json_encode($request->repeat_days);
        
        $affectedSchedules = SchedualDetail::join('schedual_masters', 'schedual_details.schedual_master_id', '=', 'schedual_masters.id')
            ->where('schedual_details.teacher_id', $request->teacher_id)
            ->whereIn('schedual_masters.date', $dates)
            ->select('schedual_details.*')
            ->get();

        $affectedSchedulesWithRelations = SchedualDetail::with([
            'schedualMaster',
            'gridSlot',
            'subject',
            'teacher',
            'room',
            'schedualMaster.course',
        ])
            ->whereIn('id', $affectedSchedules->pluck('id'))
            ->get();

        foreach ($affectedSchedulesWithRelations as $affectedSchedule) {
            Notification::send(Auth::user(), new AffectedSchedulesNotification($affectedSchedule, 'Praktikum'));
        }

        SchedualDetail::whereIn('id', $affectedSchedules->pluck('id'))->delete();
        $praktikum->save();

        return redirect()->route('course.show', $request->course_id)->with('success', 'Das Praktikum wurde erfolgreich erstellt.');
    }

    return redirect()->route('course.show', $request->course_id)->with('warning', 'Keine Übereinstimmungen für Wiederholungstage gefunden.');
}

  public function show(Praktikum $praktikum)
  {
  }
public function edit(Praktikum $praktikum)
{
    return Inertia::render('Settings/Praktikum/Edit', ['praktikum' => $praktikum]);
}
public function update(Request $request, Praktikum $praktikum)
{
    $request->validate([
        'course_id' => 'required|exists:courses,id',
        'name' => 'required|string|max:255',
        'dateRange' => 'required|array|size:2',
        'dateRange.0' => 'required|date|before:dateRange.1',
        'dateRange.1' => 'required|date|after:dateRange.0',
        'repeat_days' => 'required|array|min:1',
        'repeat_days.*' => 'required|string|in:Montag,Dienstag,Mittwoch,Donnerstag,Freitag',
    ]);

    $germanToEnglishDays = [
        'Montag' => 'Monday',
        'Dienstag' => 'Tuesday',
        'Mittwoch' => 'Wednesday',
        'Donnerstag' => 'Thursday',
        'Freitag' => 'Friday',
    ];

    $start = new DateTime($request->dateRange[0]);
    $end = new DateTime($request->dateRange[1]);
    $interval = new DateInterval('P1D');
    $daterange = new DatePeriod($start, $interval, $end->modify('+1 day'));

    $dates = [];
    foreach ($daterange as $date) {
        $day = $date->format('l');
        $dayInGerman = array_search($day, $germanToEnglishDays);
        if (in_array($dayInGerman, $request->repeat_days)) {
            $dates[] = $date->format('Y-m-d');
        }
    }

    // You can handle here if no date matches the repeating days
    // For example, return with a warning message
    if (empty($dates)) {
        return redirect()->back()->with('warning', 'Keine Übereinstimmungen für Wiederholungstage gefunden.');
    }

    // Update the praktikum details
    $praktikum->update([
        'course_id' => $request->course_id,
        'name' => $request->name,
        'start_date' => $request->dateRange[0],
        'end_date' => $request->dateRange[1],
        'repeat_days' => json_encode($request->repeat_days),
    ]);

    // Redirect back with a success message
    return redirect()->route('course.show', $request->course_id)->with('success', 'Praktikum erfolgreich aktualisiert.');
}
public function destroy(Praktikum $praktikum)
{
    $praktikum->delete();
    return redirect()->route('course.show', $praktikum->course_id)->with('success', 'Das Praktikum wurde erfolgereich gelöscht.');
}
}
