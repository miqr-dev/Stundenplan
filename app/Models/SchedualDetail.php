<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchedualDetail extends Model
{
  use HasFactory;

  protected $fillable = ['schedual_master_id', 'start_time', 'end_time', 'is_break', 'subject_id', 'teacher_id', 'room_id', 'grid_slot_id', 'date'];

  public function schedualMaster()
  {
    return $this->belongsTo(SchedualMaster::class, 'schedual_master_id');
  }

  public function gridSlot()
  {
    return $this->belongsTo(GridSlot::class);
  }

  public function subject()
  {
    return $this->belongsTo(Subject::class);
  }

  public function teacher()
  {
    return $this->belongsTo(Teacher::class);
  }

  public function room()
  {
    return $this->belongsTo(Room::class);
  }

  // Accessor to get the related template subjects and teachers.
  public function getTemplateDetailAttribute()
  {
    $course = $this->schedualMaster->course; // Assuming you have a relation from SchedualMaster to Course
    $templateId = $course->template_id;

    $template = Template::find($templateId);

    // Get the subjects and teachers related to this template.
    $subjects = $template->subjects; // Assuming you have a 'subjects' relation in Template model
    foreach ($subjects as &$subject) {
      $subject->teachers = $subject->teachers; // Load teachers for each subject
    }

    return $subjects;
  }
}
