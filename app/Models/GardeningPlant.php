<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Added this line

class GardeningPlant extends Model
{
    use HasFactory; // Added this line

    protected $fillable = [
        'user_id',
        'method',
        'image',
        'icon',
        'plant_name',
        'planting_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function progress()
    {
        return $this->hasMany(GardeningProgress::class, 'gardening_plant_id');
    }
}
