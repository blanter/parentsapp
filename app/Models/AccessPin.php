<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessPin extends Model
{
    protected $fillable = ['pin', 'is_active'];
}