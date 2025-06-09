<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Term extends Model
{
    use HasFactory;

    /**'
     *
     */
    protected $table = 'terms';

    /**
     *
     */
    protected $primaryKey = 'id';

    /**
     *
     */
    protected $fillable = ['name', 'generation_id'];

    /**
     * Get data of Terms form database
     */
    public static function getTerms() {
        $response = (object)[];

        try {
            $terms = self::all();
            $response->data = $terms;
            $response->message = 'Data get successfully!';

        } catch (Exception $e) {
            $response->data = $terms;
            $response->message = 'Something went wrong, please help check!';
        };

        return $response;
    }

    public static function getTermById($id) {
        $response = (object)[];

        try {
            $term = self::findOrFail($id);
            $response->data = $term;
            $response->message = 'Term record found!';

        } catch (ModelNotFoundException $e) {
            $response->data = $term;
            $response->message = 'Record not found!';
        };

        return $response;

    }
}
