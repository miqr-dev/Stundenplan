<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Template extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = ['name'];

  public function subjects()
  {
    return $this->belongsToMany(Subject::class)->withPivot('soll', 'ist');
  }

  public function courses()
  {
    return $this->hasMany(Course::class);
  }
}
