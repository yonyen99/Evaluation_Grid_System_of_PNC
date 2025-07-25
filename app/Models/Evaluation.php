<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;
    protected $fillable = ['class_id', 'subject_id'];

    public function class()
    {
        return $this->belongsTo(Classe::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    public function gridTypes()
    {
        return $this->hasMany(EvaluationGridType::class);
    }

    public function scores()
    {
        return $this->hasMany(EvaluationScore::class);
    }
    public function scoreStudents()
    {
        return $this->hasMany(EvaluationScoreStudent::class);
    }

    public function subjectGrids()
    {
        return $this->belongsToMany(SubjectGrid::class, 'evaluation_subject_grid');
    }
}
