<?php

use Inertia\Inertia;
use App\Models\Subject;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\CityController;
use App\Http\Controllers\GridController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\FerienController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\GridslotController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\PraktikumController;
use App\Http\Controllers\StundenplanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TeacherNotAvailableController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'todos' => auth()->user()->todos
    ]);
    })->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/settings', function () {
    return Inertia::render('Settings/Index');
})->middleware(['auth', 'verified'])->name('settings');

// Stundenplan
Route::resource('stundenplan', StundenplanController::class);
Route::get('/course/{id}/details', [StundenplanController::class, 'getSchedualDetails'])->name('course.details');


// Settings
Route::resource('city', CityController::class);
Route::resource('location', LocationController::class);
Route::resource('room', RoomController::class);
Route::resource('grid', GridController::class);
Route::resource('gridslot', GridslotController::class);
Route::resource('subject', SubjectController::class);
Route::resource('template', TemplateController::class);
Route::resource('course', CourseController::class);
Route::resource('teacher', TeacherController::class);
Route::resource('teacher-not-available', TeacherNotAvailableController::class);
Route::resource('ferien', FerienController::class);
Route::resource('praktikum', PraktikumController::class);

Route::post('/stundenplan/check-teaching-unit', [StundenplanController::class, 'checkTeachingUnit']);
Route::post('/stundenplan/check-teacher-conflicts', [StundenplanController::class, 'checkTeacherConflicts']);
Route::post('/stundenplan/teachingunit', [StundenplanController::class, 'teachingunit'])->name('stundenplan.teachingunit');
Route::post('/stundenplan/{cityId}', [StundenplanController::class, 'updateCity'])->name('stundenplan.updateCity');


// Settings custom creates 
Route::get('city/{city}/location/create', [LocationController::class, 'create'])->name('city.location.create');
Route::get('location/{location}/room/create', [RoomController::class, 'create'])->name('room.create');
Route::get('teacher/{teacher}/not-available/create', [TeacherNotAvailableController::class, 'teacher_create'])->name('teacher.not-available.create');
Route::get('grid/{grid}/gridslots/create', [GridslotController::class, 'create'])->name('gridslot.create');

//Gridslot edit
Route::get('grid/{grid}/gridslots/edit', [GridslotController::class, 'edit'])->name('gridslot.edit');


// Notification
Route::get('/notifications', [NotificationController::class,'index']);
Route::post('/notifications/{notificationId}/mark-as-read', [NotificationController::class, 'markAsRead']);


//TeacherList
Route::post('/teacherList',[StundenplanController::class, 'teacherList']);

//Todo
Route::resource('todos', TodoController::class);



require __DIR__.'/auth.php';
