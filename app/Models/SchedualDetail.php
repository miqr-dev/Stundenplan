<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchedualDetail extends Model
{
  use HasFactory;

  protected $fillable = ['schedual_master_id', 'start_time', 'end_time', 'is_break', 'subject_id', 'teacher_id', 'room_id', 'grid_slot_id'];

  public function master()
  {
    return $this->belongsTo(SchedualDetail::class);
  }
}
