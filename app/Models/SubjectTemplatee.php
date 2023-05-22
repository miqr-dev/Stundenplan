<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SubjectTemplate extends Pivot
{
    protected $table = 'subject_template';

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_subject_template')->withPivot('soll', 'ist');
    }
}