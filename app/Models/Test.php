<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Test extends Model
{
    use HasFactory;

    /**
     * Table name.
     * @var String
     */
    protected $table = 'tests';

    /**
     * Primary key.
     * @var String
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assingable.
     * @var Array
     */
    protected $fillable = [
        'name',
        'salery'
    ];

    /**
     * Get all test records from database.
     * @return ObjectRespond [ data: data_result, message: result_message ]
     */
    public static function getTests()
    {
        $respond = (object)[];

        try {
            $tests = self::all();

            $respond->data    = $tests;
            $respond->message = 'Successful getting all test records from database';
        } catch (Exception $e) {
            $respond->data    = false;
            $respond->message = 'Problem occured while trying to get all test records from database!';
        }

        return $respond;
    }


    /**
     * Get Test record baed on given id parameter from database.
     * @param Integer $id
     * @return ObjectRespond [ data: data_result, message: result_message ]
     */
    public static function getTest($id)
    {
        $respond = (object)[];

        try {
            $getTest = self::findOrFail($id);

            $respond->data    = $getTest;
            $respond->message = 'Test record found';
        } catch (ModelNotFoundException $e) {
            $respond->data    = false;
            $respond->message = 'Test record not found!';
        }

        return $respond;
    }
}
