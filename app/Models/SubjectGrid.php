<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectGrid extends Model
{
    use HasFactory;
    protected $fillable = ['grid_name', 'percentage', 'subject_id'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    
}
