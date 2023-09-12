<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feiertag extends Model
{
    use HasFactory;
    protected $fillable = ['bundesland_id', 'year', 'date', 'name'];

    public function bundesland()
{
    return $this->belongsTo(Bundesland::class);
}
}
