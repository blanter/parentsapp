<?php
// app/Models/Score.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $fillable = ['parent_id','activity','score','deskripsi'];

    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id');
    }
}