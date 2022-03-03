<?php

namespace App\Models;

include_once('Model.php');

use App\Models\Model;

class Car extends Model {

    protected $attributes = [
        'id' => null,
        'brand' => null,
        'model' => null,
        'color' => null,
        'car_weight' => null,
        'top_speed' => null,
        'chasis_number' => null,
        'country_of_origin' => null
    ];

    protected $allowed = [
        'brand', 'model', 'color', 'car_weight', 
        'chasis_number', 'top_speed', 'country_of_origin'
    ];

    protected static $table = 'cars';
    protected static $useTimestamps = true;

    public function __construct() {
        $this->chasis_number = uniqid("FML");
    }

    public static function createTable($conn) {
        try {
            $conn->exec(
                'CREATE TABLE cars(
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    brand VARCHAR(100),
                    model VARCHAR(150),
                    color VARCHAR(100) NULL,
                    car_weight FLOAT NULL,
                    top_speed BIGINT NULL,
                    chasis_number VARCHAR(150),
                    country_of_origin VARCHAR(100)
                );'
            );
            self::setTimeStampsOnTable($conn, self::$table);
        }catch(Exception $e) {
            error_log($e->getMessage());
        }
    }
}