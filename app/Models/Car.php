<?php

namespace App\Models;

require __DIR__ . '/../../vendor/autoload.php';

use MyLittleFramework\Model\Model;
use MyLittleFramework\DB\Connection;
use Exception;

class Car extends Model {
    
    protected $attributes = [
        'id', 'brand', 'model','color',
        'car_weight', 'top_speed', 'chasis_number', 'country_of_origin'
    ];

    protected $allowed = [
        'brand', 'model', 'color', 'car_weight', 
        'chasis_number', 'top_speed', 'country_of_origin'
    ];

    protected static $table = 'cars';
    protected string $primaryKey = 'id';
    protected static $useTimestamps = true;

    public function __construct($chasis_number = null) {

        parent::__construct(); //do not delete this line

        if($chasis_number === null) {
            $this->chasis_number = uniqid("FML");
        }
        else {
            $this->chasis_number = $chasis_number;
        }
    }

    public static function createTable(): void {
        try {
            $conn = Connection::getInstance()->getConnection();
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