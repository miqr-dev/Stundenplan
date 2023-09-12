<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = ['name'];

  public function locations()
  {
    return $this->hasMany(Location::class);
  }

  public function teachers()
  {
    return $this->belongsToMany(Teacher::class, 'city_teacher');
  }

  public function courses()
  {
    return $this->hasMany(Course::class);
  }
  public function bundesland()
{
    return $this->belongsTo(Bundesland::class);
}
}
