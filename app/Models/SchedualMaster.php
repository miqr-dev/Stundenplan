<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchedualMaster extends Model
{
    use HasFactory;

    protected $fillable = ['calendar_week','course_id','date'];
  public function schedualDetails()
  {
    return $this->hasMany(SchedualDetail::class);
  }

    public function course()
    {
      return $this->belongsTo(Course::class);
    }
}
