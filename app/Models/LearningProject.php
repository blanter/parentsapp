<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'title',
        'description',
        'type',
        'progress_percentage',
        'image',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function students()
    {
        $database = config('database.connections.mysql.database');
        return $this->belongsToMany(Student::class, $database . '.learning_project_student', 'learning_project_id', 'student_id');
    }

    public function logs()
    {
        return $this->hasMany(LearningLog::class);
    }
}
