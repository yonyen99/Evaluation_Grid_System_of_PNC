<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Generation extends Model
{
    use HasFactory;

    /**
     * Table name = generation
     */
    protected $table = 'generations';



    /**
     * primary key = id
     */
    protected $primaryKey = 'id';



    /**
     * The attribute key that in generation page
     */
    protected $fillable = ['name', 'grade', 'description', 'terms', 'teacher_id'];


    /**
     * Get all data of generation from database
     *
     */

    public static function getGenerations()
    {
        $response = (object)[];

        try {
            $generations = self::all();

            $response->data = $generations;
            $response->message = 'Generation get successfully!';

        } catch (Exception $e) {
            $response->data = false;
            $response->message = 'Generation have any problem';
        }

        return $response;
    }


    /**
     * Get data of generation by id
     */

    public static function  getGenerationById($id)
    {
        $response = (object)[];

        try {
            $generation = self::findOrFail($id);
            $response->data = $generation;
            $response->message = 'Generation get by id successfully!';

        } catch (ModelNotFoundException $e) {
            $response->data = false;
            $response->message = 'Generation not found!';
        }

        return $response;
    }
}
