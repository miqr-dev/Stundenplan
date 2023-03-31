<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchedualMaster extends Model
{
    use HasFactory;

    protected $fillable = ['date','course_id'];

    public function details()
    {
      return $this->hasMany(SchedualDetail::class);
    } 
}
