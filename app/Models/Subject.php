<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Subject extends Model
{
    use HasFactory;

    /**
     * Table name.
     * @var String
     */
    protected $table = 'subjects';

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
        'description'
    ];

    /**
     * Get all test records from database.
     * @return ObjectRespond [ data: data_result, message: result_message ]
     */
    public static function getSubjects()
    {
        $respond = (object)[];

        try {
            $subjects = self::all();

            $respond->data    = $subjects;
            $respond->message = 'Successful getting all subject records from database';
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
    public static function getSubject($id)
    {
        $respond = (object)[];

        try {
            $getSubject = self::findOrFail($id);

            $respond->data    = $getSubject;
            $respond->message = 'Test record found';
        } catch (ModelNotFoundException $e) {
            $respond->data    = false;
            $respond->message = 'Test record not found!';
        }

        return $respond;
    }
}
