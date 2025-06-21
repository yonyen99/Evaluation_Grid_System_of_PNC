<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Teacher extends Model
{
    use HasFactory;
    /**
     * Table name.
     * @var String
     */
    protected $table = 'teachers';

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
        'first_name',
        'last_name',
        'email',
        'phone',
        'profile',
        'password' 
    ];

    
    /**
     * *********************************
     *      Relationship table
     * *********************************
     */

     

    /**
     * *****************************
     *      Modules function
     * *****************************
     */

        /**
         * Get all data of teachers from database
         * @return response
         */

        public static function getTeachers()
        {
            $response = (object)[];

            try {
                $teachers = self::all();

                $response->data = $teachers;
                $response->message = 'Teacher get successfully!';

            } catch (Exception $e) {
                $response->data = false;
                $response->message = 'Teacher have any problem';
            }

            return $response;
        }


        /**
         * Get data of Teacher by id.
         * @return response
         */

        public static function  getTeacherById($id)
        {
            $response = (object)[];

            try {
                $teacher = self::findOrFail($id);
                $response->data = $teacher;
                $response->message = 'teacher get by id successfully!';

            } catch (ModelNotFoundException $e) {
                $response->data = false;
                $response->message = 'teacher not found!';
            }

            return $response;
        }
}
