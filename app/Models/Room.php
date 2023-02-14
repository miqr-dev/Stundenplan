<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable= ['name', 'room_number','floor','capacity','location_id'];

    public function location()
    {
      return $this->belongsTo(Location::class);
    }
}
