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
     * Table name.
     * @var String
     */
    protected $table = 'generations';

    /**
     * Primary key.
     * @var String
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     * @var Array
     */
    protected $fillable = [
        'name',
    ];

    
    /**
     * *********************************
     *      Relationship table
     * *********************************
     */

        /**
         * Many Terms to one Generation relationship.
         * @return App\Models\Term
         */
        public function terms(){
            return $this->hasMany(Term::class);
        }

    /**
     * *****************************
     *      Modules function
     * *****************************
     */

        /**
         * Get all data of generation from database
         * @return response
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
         * Get data of generation by id.
         * @return response
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
