<?php

namespace MyLittleFramework\DB;

require __DIR__ . '/../../vendor/autoload.php';

use PDO;

class Connection {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }
    
    public function connect(): PDO {
        try {
            $pdo = new PDO("sqlite:".$this->db);
            //if I get an error message, i can show it inside the website
            //thanks to these parameters
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }
        
    }
}