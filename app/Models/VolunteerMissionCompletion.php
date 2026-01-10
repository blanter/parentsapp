<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VolunteerMissionCompletion extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'volunteer_mission_id', 'completed_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mission()
    {
        return $this->belongsTo(VolunteerMission::class, 'volunteer_mission_id');
    }
}
