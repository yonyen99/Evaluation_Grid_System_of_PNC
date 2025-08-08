<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoreTable extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'evaluation_score_student_id'];

    public function subColumns()
    {
        return $this->hasMany(ScoreSubColumn::class);
    }
    
}
