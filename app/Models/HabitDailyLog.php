<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HabitDailyLog extends Model
{
    protected $fillable = ['habit_id', 'log_date', 'is_completed'];

    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }
}
