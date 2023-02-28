<?php

namespace App\Rules;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\Rule;

class TimeNotInRange implements Rule
{
  protected $table;
  protected $id;
  protected $startTime;
  protected $endTime;


  public function __construct($table, $id = null, $startTime = null, $endTime = null)
  {
    $this->table = $table;
    $this->id = $id;
    $this->startTime = $startTime;
    $this->endTime = $endTime;
  }

  public function passes($attribute, $value)
  {
    $startTime = $this->startTime ?? $value;
    $endTime = $this->endTime ?? date('H:i:s', strtotime('+90 minutes', strtotime($startTime)));

    $query = DB::table($this->table)
      ->where(function ($query) use ($startTime, $endTime) {
        $query->where(function ($query) use ($startTime, $endTime) {
          $query->where('starttime', '>=', $startTime)->where('starttime', '<', $endTime);
        })->orWhere(function ($query) use ($startTime, $endTime) {
          $query->where('starttime', '<=', $startTime)->where('endtime', '>', $startTime);
        });
      });

    if ($this->id) {
      $query->where('id', '!=', $this->id);
    }

    $count = $query->count();

    return $count === 0;
  }

  public function message()
  {
    return 'The :attribute is already taken for this time slot.';
  }
}
