<?php

namespace App\Models;

use Exception;
use Generator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;


    /**
     * Table name 
     * @var String
     */
    protected $table = 'terms';

    /**
     * Primary key
     * @var String
     */
    protected $primaryKey = 'id';

    /**
     * Attribute that are mass assignable.
     * @var Array
     */
    protected $fillable = [
        'name',
        'generation_id'

    ];

    /**
     * *********************************
     *      Table Relationship
     * *********************************
     */

    // public function classes()
    // {
    //     return $this->belongsToMany(Classe::class, 'add_class_to_terms', 'term_id', 'class_id');
    // }
    public function classes()
    {
        // Important: no need to filter by generation_id here
        // Because the term_id in classes already points to this term
        return $this->hasMany(Classe::class);
    }
}
