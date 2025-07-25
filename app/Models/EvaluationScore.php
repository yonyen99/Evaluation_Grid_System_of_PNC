<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationScore extends Model
{
    use HasFactory;
    
    protected $fillable = ['evaluation_id', 'evaluation_name', 'point'];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function scoreStudents()
    {
        return $this->hasMany(EvaluationScoreStudent::class, 'evaluation_type_id');
    }

    
}
