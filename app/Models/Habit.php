<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    protected $fillable = ['user_id', 'title'];

    public function logs()
    {
        return $this->hasMany(HabitDailyLog::class);
    }
}
