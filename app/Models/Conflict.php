<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conflict extends Model
{
  use HasFactory, SoftDeletes;
  protected $guarded = [];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function firstCourse()
  {
    return $this->belongsTo(Course::class, 'first_course_id');
  }

  public function secondCourse()
  {
    return $this->belongsTo(Course::class, 'second_course_id');
  }
  public function teacher()
{
    return $this->belongsTo(Teacher::class);
}
}
