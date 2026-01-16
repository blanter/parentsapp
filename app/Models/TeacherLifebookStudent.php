<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherLifebookStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'student_id',
    ];

    /**
     * Get the teacher for this lifebook assignment
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    /**
     * Get the student for this lifebook assignment
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
