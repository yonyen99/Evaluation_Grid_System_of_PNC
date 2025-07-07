<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddClassToTerm extends Model
{
    use HasFactory;
    protected $table = 'add_class_to_terms';

    protected $fillable = ['term_id', 'class_id'];
}
