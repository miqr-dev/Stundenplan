<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherNotAvailable extends Model
{
  use HasFactory, SoftDeletes;

  protected $dates = [
    'created_at',
    'updated_at',
    'deleted_at',
    'awaystartdate',
    'awayenddate'
];

  public function teacher()
  {
    return $this->belongsTo(Teacher::class);
  }
}
