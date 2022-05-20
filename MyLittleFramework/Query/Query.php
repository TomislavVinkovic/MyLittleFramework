<?php

namespace MyLittleFramework\Query;

use MyLittleFramework\DB\Connection;
use Exception;

class Query {
    private $conn;
    private string $className;
    private string $table;
    private string $sql;
    private $supportedOperators = ['=', 'LIKE', '<>', '>', '<', '>=', '<='];

    public function __construct(string $class) {
        $this->conn = Connection::getInstance()->getConnection();
        $this->className = $class;
        $this->table = $class::getClassTable();
        $this->sql = "SELECT * FROM $this->table WHERE";
    }

    private function checkOperators($operator) {
        if(!in_array(strtoupper($operator), $this->supportedOperators)) {
            throw new Exception ("Unsupported operator");
        }
    }

    public function where(string $propName, string $operator, $propVal) {

        $this->checkOperators($operator);
        $operator = strtoupper($operator);

        if($this->sql !== "SELECT * FROM $this->table WHERE"){
            $this->andWhere($propName, $operator, $propVal);
        }

        else {
            if(is_string($propVal)) {
                $this->sql = $this->sql . " $propName $operator '$propVal'";
            }
            else {
                $this->sql = $this->sql . " $propName $operator $propVal";
            }
        }
        return $this;
        
    }

    public function orWhere(string $propName, string $operator, $propVal) {

        $this->checkOperators($operator);
        $operator = strtoupper($operator);

        if($this->sql === "SELECT * FROM $this->table WHERE"){
            $this->where($propName, $operator, $propVal);
        }

        else {
            if(is_string($propVal)) {
                $this->sql = $this->sql . " OR $propName $operator '$propVal'";
            }
            else {
                $this->sql = $this->sql . " OR $propName $operator $propVal";
            }
        }
        
        return $this;
        
    }

    public function andWhere(string $propName, string $operator, $propVal) {

        $this->checkOperators($operator);
        $operator = strtoupper($operator);

        if($this->sql === "SELECT * FROM $this->table WHERE"){
            $this->where($propName, $operator, $propVal);
        }

        else {
            if(is_string($propVal)) {
                $this->sql = $this->sql . " AND $propName $operator '$propVal'";
            }
            else {
                $this->sql = $this->sql . " AND $propName $operator $propVal";
            }
        }
        
        return $this;
    }

    private function excludeSoftDeletes() {
        if($this->sql === "SELECT * FROM $this->table WHERE") {
            $this->sql = $this->sql . " deleted_at IS NULL";
        }
        else {
            $this->sql = $this->sql . " AND deleted_at IS NULL";
        }
    }

    public function get(bool $excludeSoftDeletes = true) {
        if($excludeSoftDeletes) {
            $this->excludeSoftDeletes();
        }
        
        $statement = $this->conn->prepare($this->sql);
        $statement->execute();
        $data = $statement->fetchAll();
        if(!$data) {
            return null;
        }
        $return_data = [];
        foreach($data as $obj_arr) {
            $obj = new $this->className;
            $keys = explode(',', $obj->getKeys());
            foreach($keys as $key) {
                if(!in_array($key, array_keys($obj->getTimestamps())) && $key !== $obj->getPrimaryKeyName()) {
                    $obj->setAttribute($key, $obj_arr[$key]);
                }
                $obj->setPrimaryKey($obj_arr[$obj->getPrimaryKeyName()]);
                $obj->setTimeStamps($obj_arr['created_at'], $obj_arr['updated_at'], $obj_arr['deleted_at']);
                
            }
            $return_data[] = $obj;
        }

        return $return_data;
    }
}