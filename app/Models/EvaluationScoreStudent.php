<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationScoreStudent extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'grid_type_id',
        'evaluation_type_id',
        'score',
        'evaluation_score_id'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function evaluationScore()
    {
        return $this->belongsTo(EvaluationScore::class, 'evaluation_score_id');
    }
}
