<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GardeningProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'gardening_plant_id',
        'user_id',
        'image',
        'description',
        'score',
        'report_month',
        'report_year',
        'report_date',
    ];

    public function plant()
    {
        return $this->belongsTo(GardeningPlant::class, 'gardening_plant_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
