<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bundesland extends Model
{
  use HasFactory;

  protected $fillable = ['name', 'short_name'];

  public function cities()
  {
    return $this->hasMany(City::class);
  }

  public function feiertags()
  {
    return $this->hasMany(Feiertag::class);
  }
}
