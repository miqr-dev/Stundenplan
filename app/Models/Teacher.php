<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
  use HasFactory, SoftDeletes;

  protected $guarded = [];

  public function teacherNotAvailable()
  {
    return $this->hasMany(TeacherNotAvailable::class);
  }
  public function cities()
  {
    return $this->belongsToMany(City::class, 'city_teacher');
  }
  public function subjects()
  {
    return $this->belongsToMany(Subject::class, 'subject_teacher');
  }
  public function conflicts()
{
    return $this->hasMany(Conflict::class);
}

}
