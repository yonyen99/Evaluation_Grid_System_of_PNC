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
}
