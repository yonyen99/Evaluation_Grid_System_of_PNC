<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClasseStudent extends Model
{
    use HasFactory;

    protected $fillable = ['class_id', 'student_id'];

    // ✅ Add this relationship
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // (Optional) Add if you want reverse relation from class too
    public function classe()
    {
        return $this->belongsTo(Classe::class, 'class_id');
    }
}
