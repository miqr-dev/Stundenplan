<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Praktikum extends Model
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
    'course_id',
    'name',
    'start_date',
    'end_date',
    'repeat_days'
  ];
  public function course()
  {
    return $this->belongsTo(Course::class);
  }
}
