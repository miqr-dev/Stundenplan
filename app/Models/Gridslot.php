<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gridslot extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'grid_id',
        'start_time',
        'end_time',
        'is_break',
    ];

    protected $casts = [
        'is_break' => 'boolean',
    ];

    public function grid()
    {
        return $this->belongsTo(Grid::class);
    }
}
