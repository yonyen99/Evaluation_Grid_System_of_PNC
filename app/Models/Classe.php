<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = ['name', 'generation_id', 'term_id'];

    public function generation()
    {
        return $this->belongsTo(Generation::class);
    }

    public function subjectTeachers()
    {
        return $this->hasMany(ClassSubjectTeacher::class, 'class_id');
    }

    // ✅ A class has many students through the pivot table
    public function students()
    {
        return $this->belongsToMany(Student::class, 'classe_students', 'class_id', 'student_id');
    }

    public function terms()
    {
        return $this->belongsToMany(Term::class, 'add_class_to_terms', 'class_id', 'term_id');
    }
    public function term()
    {
        return $this->belongsTo(Term::class);
    }
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subject_teachers', 'class_id', 'subject_id')->distinct();
    }
}
