<?php
// app/Models/ParentModel.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    protected $table = 'parents';
    protected $fillable = ['name','image'];

    public function scores()
    {
        return $this->hasMany(Score::class, 'parent_id');
    }
}