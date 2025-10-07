<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'gender',
        'email',
        'province_id',
        'generation_id',
        'profile',
        'username',
        'phone',
        'password',
        'db',
    ];
    // Each student belongs to one province
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    // Each student belongs to one generation
    public function generation()
    {
        return $this->belongsTo(Generation::class);
    }
    public function classeStudents()
    {
        return $this->hasMany(ClasseStudent::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studentDetail()
    {
        return $this->hasOne(StudentDetail::class, 'student_id');
    }
}
