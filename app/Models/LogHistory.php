<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LogHistory extends Model
{
    use HasFactory;
    use HasFactory;

    /**
     * Table name.
     * @var String
     */
    protected $table = 'log_histories';

    /**
     * Primary key.
     * @var Integer
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     * @var Array
     */
    protected $fillable = [
        'username',
        'user_id',
        'log_header',
        'description',
        'permission_slug',
    ];

    /**
     * ###############################
     *     Module Helper Functions
     * ###############################
     */
    // Log History Helper Function [BEGIN]
    /**
     * Get all log history records from database.
     * @return ObjectRespond [ data: date_result, message: result_message ]
     */
    public static function getLogHistories()
    {
        $respond = (object)[];

        try {
            $logHistories = LogHistory::orderBy('created_at', 'DESC')->get();
            $respond->data    = $logHistories;
            $respond->message = 'Successful getting all log history records from database';
        } catch (Exception $ex) {
            $respond->data    = false;
            $respond->message = 'Problem occured while trying to get all log history records from database!';
        }
        return $respond;
    }

    /**
     * Get specific log history record based on given id or by log_header parameters from database.
     * @param String $attribute
     * @param String $queryBy [ id, header ]
     * 
     * @return ObjectRespond [ data: date_result, message: result_message ]
     */
    public static function getLogHistory($attribute, $queryBy)
    {
        $respond = (object)[];

        try {

            $logHistory = $queryBy == 'header' ? LogHistory::where('log_header', $attribute)->get() : LogHistory::findOrFail($attribute);

            $respond->data    = $logHistory;
            $respond->message = 'Log history record found';
        } catch (ModelNotFoundException $ex) {
            $respond->data    = false;
            $respond->message = 'Log history not records!';
        }

        return $respond;
    }

    /**
     * Get collections of log history records by given header permission collection of log_header.
     * @param Collection $headerPermissionsCollection
     * @return ObjectRespond [ data: date_result, message: result_message ]
     */
    public static function findArrOfLogHistoryLogHeader($headerPermissionsCollection)
    {
        $respond = (object)[];

        try {
            $logHistoryRecords = new Collection();

            foreach ($headerPermissionsCollection as $permissionLogHeader) {
                $tempLogHistory = LogHistory::where('permission_slug', $permissionLogHeader)->get();

                if (count($tempLogHistory)) {
                    foreach ($tempLogHistory as $item) {
                        $logHistoryRecords->push($item);
                    }
                }
            }

            $respond->data    = $logHistoryRecords;
            $respond->message = 'Log history record found';
        } catch (ModelNotFoundException $ex) {
            $respond->data    = false;
            $respond->message = 'Log history not records!';
        }

        return $respond;
    }

    /**
     * Get all loghistory base on pagination.
     * @return ObjectRespond [ data: date_result, message: result_message ]
     */
    public static function getLogHistoryBaseOnPagination()
    {
        $respond = (object)[];
        // get records
        $logHistories = LogHistory::getLogHistories();
        if (!$logHistories->data) {
            return $logHistories;
        }
        $logHistories = $logHistories->data;

        try {
            $respond          = $logHistories;
            $respond->message = 'All log history records found';
        } catch (Exception $ex) {
            $respond->data    = false;
            $respond->message = 'Problem occured while trying to get all log history records from database!';
        }

        return $respond;
    }
    // Log History Helper Function [END]

    /**
     * #####################
     *     Relationships
     * #####################
     */
        /**
         * Many log histories to one user
         * @return \App\Models\User
         */
        public function user()
        {
            return $this->belongsTo(
                User::class,
                'user_id',
            );
        }

        /**
         * Many log histories to many roles (Polymorphic)
         * @return Spatie/Permission/Models/Role
         */
        public function roles(){
            return $this->morphedByMany(
                Role::class,
                'historyables',
            );
        }

}
