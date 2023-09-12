<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ferien extends Model
{
  use HasFactory;

  protected $dates = [
    'created_at',
    'updated_at',
    'start_date',
    'end_date'
  ];

  protected $fillable = [
    'course_id',
    'name',
    'start_date',
    'end_date',
  ];

  public function course()
  {
    return $this->belongsTo(Course::class);
  }
}
