<?php

namespace MyLittleFramework\DB;

require __DIR__ . '/../../vendor/autoload.php';

use PDO;
use PDOException;

class Connection {

    private static $instance = null;
    
    //i have to develop some kind of enviorment to store env variables
    private const CONNECTION_STRING = 'database.db';
    private $conn;

    private function __construct() {
        try {
            $this->conn = new PDO("sqlite:". self::CONNECTION_STRING);
            //if I get an error message, i can show it inside the website
            //thanks to these parameters
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //This attribute is used to get only a single copy of the data
            //and not both by a numerical id and a string id
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

    //this class is a singleton
    //it means that it will always return one, same instance from memory, therefore
    //reducing the memory footprint and possibly costs
    public static function getInstance() {
        if(!self::$instance) {
            self::$instance = new Connection();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->conn;
    }
}