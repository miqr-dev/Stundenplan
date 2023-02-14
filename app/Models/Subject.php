<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function templates()
    {
        return $this->belongsToMany(Template::class);
    }
        public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'subject_teacher');
    }
}
