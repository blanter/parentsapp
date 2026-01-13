<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'learning_project_id',
        'teacher_id',
        'user_id',
        'content',
        'progress_percentage',
        'image',
    ];

    public function project()
    {
        return $this->belongsTo(LearningProject::class, 'learning_project_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
