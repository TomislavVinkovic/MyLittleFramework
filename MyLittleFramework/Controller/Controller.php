<?php

namespace MyLittleFramework\Controller;

require __DIR__ . '/../../vendor/autoload.php';

use MyLittleFramework\DB\Connection;
use PDO;

abstract class Controller {
    protected $conn;
    protected $db;

    protected const OK = 200;
    protected const CREATED = 201;
    protected const NOT_FOUND = 404;
    protected const INTERNAL_SERVER_ERROR = 500;

    public function __construct() {
        $this->conn = new Connection("database.db");
        $this->db = $this->conn->connect();
    }

    protected function view($viewName) {
        echo "To be implemented";
    }

    //I will expand this function later with a custom
    //response object
    protected static function redirect($url, $responseCode) {
        header("Location: $url", true, $responseCode);   
    }
}