<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchedualDetail extends Model
{
  use HasFactory;

  protected $fillable = ['schedual_master_id', 'start_time', 'end_time', 'is_break', 'subject_id', 'teacher_id', 'room_id', 'grid_slot_id','date'];

  public function master()
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
}