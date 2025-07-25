<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GridType extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'subject_grid_id',
        'classe_student_id',
        'value',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subjectGrid()
    {
        return $this->belongsTo(SubjectGrid::class);
    }

    public function classeStudent()
    {
        return $this->belongsTo(ClasseStudent::class);
    }
}
