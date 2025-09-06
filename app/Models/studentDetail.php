<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class studentDetail extends Model
{
      use HasFactory;

    // Table name (optional if Laravel can infer correctly)
    protected $table = 'student_details';

    protected $fillable = [
        'student_id',
        'father',
        'mother',
        'phone',
        'address',
        'mom_contract',
    ];


    public function student()
    {
        return $this->belongsTo(Student::class);
    }

}
