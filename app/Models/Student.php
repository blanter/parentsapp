<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $connection = 'lifebook_users';
    protected $table = 'users';

    /**
     * Scope a query to only include active students.
     */
    public function scopeActive($query)
    {
        return $query->where('role', 'murid')
            ->where('lulus', '0');
    }
}
