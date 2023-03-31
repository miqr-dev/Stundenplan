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

  protected $guarded = [];

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
}
