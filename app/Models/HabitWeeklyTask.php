<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HabitWeeklyTask extends Model
{
    protected $fillable = ['user_id', 'title', 'month', 'year', 'week_index', 'is_completed'];

    protected $casts = [
        'is_completed' => 'boolean',
    ];
}
