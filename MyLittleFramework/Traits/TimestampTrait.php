<?php

namespace MyLittleFramework\Traits;

use PDO;
use Carbon\Carbon;
use Exception;
use Carbon\Exceptions\InvalidFormatException;

trait TimestampTrait {
    protected $timestamps = [
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null
    ];

    public function getTimestamps() {
        return $this->timestamps;
    }

    protected static function excludeDeletes($sql) {
        if(str_contains($sql, 'WHERE') || str_contains($sql, 'where')) {
            return $sql . ' AND deleted_at IS NULL';
        }
        else {
            return $sql . ' WHERE deleted_at IS NULL';
        }
    }

    private static function checkStamp($stamp) {
        try {
            Carbon::parse($stamp);
            return true;
        }catch(InvalidFormatException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function setTimeStamps($created_at = null, $updated_at = null, $deleted_at = null) {
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