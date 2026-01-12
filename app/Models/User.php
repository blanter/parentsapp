<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_approved',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function students()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany(Student::class, "$database.parent_student", 'user_id', 'student_id');
    }

    public function volunteerCompletions()
    {
        return $this->hasMany(VolunteerMissionCompletion::class);
    }

    public function lifebookJourneys()
    {
        return $this->hasMany(LifebookJourney::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'user_id');
    }
}
