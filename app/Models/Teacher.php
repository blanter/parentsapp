<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $connection = 'lifebook_users';
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function scopeActiveTeacher($query)
    {
        return $query->where('role', 'guru');
    }

    public function students()
    {
        $mainDatabase = config('database.connections.mysql.database');
        return $this->belongsToMany(Student::class, $mainDatabase . '.teacher_student', 'teacher_id', 'student_id');
    }
}
