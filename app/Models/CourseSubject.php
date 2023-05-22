<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseSubject extends Pivot
{
    protected $table = 'course_subject';

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }
}
