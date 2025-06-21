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

       /**
         * Many terms to one generation relationship.
         * @return App\Models\Generation
         */
        public function product(){
            return $this->belongsTo(
                Generation::class,
                'product_id',
            );
        }
    /**
     * *************************
     *      Modules Function
     * *************************
     */

        /**
         * Get all data of Term from database
         * @return response
         */

        public static function getTerms()
        {
            $response = (object)[];

            try {
                $terms = self::all();

                $response->data = $terms;
                $response->message = 'Term get successfully!';

            } catch (Exception $e) {
                $response->data = false;
                $response->message = 'Term have any problem';
            }

            return $response;
        }


        /**
         * Get data of term by id.
         * @return response
         */

        public static function  getTermById($id)
        {
            $response = (object)[];

            try {
                $term = self::findOrFail($id);
                $response->data = $term;
                $response->message = 'Term get by id successfully!';

            } catch (Exception $e) {
                $response->data = false;
                $response->message = 'Term not found!';
            }

            return $response;
        }
}
