<?php

namespace App\Traits;

use PDO;
use Carbon\Carbon;

trait TimestampTrait {
    protected $timestamps = [
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null
    ];

    protected static function setTimeStampsOnTable($conn, $table) {
        $sql = "ALTER TABLE $table
        ADD created_at datetime";

        $conn->exec($sql);

        $sql = "ALTER TABLE $table
        ADD updated_at datetime";
        
        $conn->exec($sql);

        $sql = "ALTER TABLE $table
        ADD deleted_at datetime";
        
        $conn->exec($sql);
    }

    private static function checkStamp($stamp) {
        try {
            Carbon::parse($stamp);
            return true;
        }catch(InvalidFormatException $e) {
            error_log(e->getMessage());
            return false;
        }
    }

    protected function setTimeStamps($created_at = null, $updated_at = null, $deleted_at = null) {
        try {
            if($created_at !== null && self::checkStamp($created_at)) {
                $this->timestamps['created_at'] = $created_at;
            }
    
            if($updated_at !== null && self::checkStamp($updated_at)) {
                $this->timestamps['updated_at'] = $updated_at;
            }
    
            if($deleted_at !== null && self::checkStamp($deleted_at)) {
                $this->timestamps['deleted_at'] = $deleted_at;
            }
        }catch(Exception $e) {
            error_log($e->getMessage());
        }
        
    }
}