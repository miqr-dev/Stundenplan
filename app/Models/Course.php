<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name','type','lbrn','city_id','template_id'];

    public function city()
    {
      return $this->belongsTo(City::class);
    }

    public function template()
    {
      return $this->belongsTo(Template::class);
    }
}