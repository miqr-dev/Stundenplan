<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grid extends Model
{
  use HasFactory;
  protected $fillable = [
    'name',
    'description',
    'user_id',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function gridslots()
  {
    return $this->hasMany(Gridslot::class);
  }
  
  public function course()
  {
    return $this->hasOne(Course::class);
  }
}
