<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentJournal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'bulan',
        'tahun',
        'pendekatan',
        'interaksi',
        'parent_filled_at',
        'teacher_id',
        'teacher_name',
        'teacher_reply',
        'teacher_replied_at',
        'lifebook_teacher_id',
        'lifebook_teacher_name',
        'lifebook_teacher_reply',
        'lifebook_teacher_replied_at',
    ];

    protected $casts = [
        'parent_filled_at' => 'datetime',
        'teacher_replied_at' => 'datetime',
        'lifebook_teacher_replied_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
