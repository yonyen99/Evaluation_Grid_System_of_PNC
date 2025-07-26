<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationGridType extends Model
{
    use HasFactory;
    protected $fillable = ['grid_type_id', 'total', 'evaluation_id'];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function gridType()
    {
        return $this->belongsTo(GridType::class);
    }

    public function subjectGrid()
    {
        return $this->belongsTo(SubjectGrid::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
