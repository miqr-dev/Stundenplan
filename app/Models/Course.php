<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
  use HasFactory, SoftDeletes;

  protected $dates = [
    'created_at',
    'updated_at',
    'deleted_at',
    'start_date',
    'end_date'
  ];

  protected $fillable = [
    'room_id',
    'template_id',
    'grid_id',
    'name',
    'start_date',
    'end_date',
    'color',
    'type',
    'lbrn',
    'description',
    'active',
  ];

  public function room()
  {
    return $this->belongsTo(Room::class);
  }

  public function location()
  {
    return $this->belongsTo(Location::class);
  }
  public function city()
  {
    return $this->belongsTo(City::class);
  }

  public function template()
  {
    return $this->belongsTo(Template::class);
  }

  public function grid()
  {
    return $this->belongsTo(Grid::class);
  }
  public function subjects()
  {
    return $this->belongsToMany(Subject::class)
      ->using(CourseSubject::class)
      ->withPivot('template_id', 'soll', 'ist')
      ->withTimestamps();
  }

  public function schedualMaster()
  {
    return $this->hasOne(SchedualMaster::class);
  }
}
