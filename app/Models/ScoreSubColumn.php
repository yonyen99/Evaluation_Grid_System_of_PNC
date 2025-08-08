<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoreSubColumn extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'set_score', 'score_table_id'];

    public function scoreTable()
    {
        return $this->belongsTo(ScoreTable::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
