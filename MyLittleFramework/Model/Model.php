<?php

namespace MyLittleFramework\Model;

use MyLittleFramework\Traits\AttributeTrait;
use MyLittleFramework\Traits\TimestampTrait;
use MyLittleFramework\DB\Connection;
use MyLittleFramework\Exceptions\NonExistantModelException;
use MyLittleFramework\Exceptions\UnimplementedMethodException;
use MyLittleFramework\Exceptions\NonExistantPropertyException;
use Carbon\Carbon;
use Exception;

abstract class Model {

    use AttributeTrait;
    use TimestampTrait;

    protected static $table;
    protected static $useTimestamps;
    
    public function __construct() {
        $this->setPrimaryKey(null);
        foreach($this->attributes as $attr) {
            if($attr !== $this->primaryKey) {
                $this->setAttribute($attr, null);
            }
        }
    }

    public function __get(string $propertyName): mixed {
        return $this->getAttribute($propertyName);
    }

        
    public function __set(string $propertyName, mixed $propertyValue): void {
        if($propertyName === $this->primaryKey) {
            $this->setPrimaryKey($propertyValue);
            return;
        }
        $this->setAttribute($propertyName, $propertyValue);
    }

    public function __call($method, $arguments) {
        throw new UnimplementedMethodException(__METHOD__);
    }

    public function toArray(): array {
        throw new UnimplementedMethodException(__METHOD__);
    }

    public function __toString(): string {
        throw new UnimplementedMethodException(__METHOD__);
    }

    public function __sleep() {
        return $this->attributes;
    }

    public function __wakeup() {
        throw new UnimplementedMethodException(__METHOD__);
    }


    public function __isset(string $propertyName): bool {
        try {
            return (null !== $this->getAttribute($propertyName));
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public function __unset(string $propertyName): void {
        try {
            if($propertyName === null) {
                $this->setPrimaryKey(null);
                return;
            }
            $this->setAttribute($propertyName, null);
        }
        catch(Exception $_){
            throw new NonExistantPropertyException($propertyName, get_class($this));
        }
        
    }

    private function getKeys(bool $pk = false): string {
        $arr_keys = $this->attributes;
        if(static::$useTimestamps === true) {
            $arr_keys = array_merge($arr_keys, array_keys($this->timestamps));
        }
        $keys = "";
        foreach($arr_keys as $key=>$val) { //to get all fields except the pk
            if($val === $this->primaryKey && $pk === false) continue;
            $keys = $keys . $val . ',';
        }
        $keys = substr($keys, 0, -1); //micemo zadnji zarez
        return $keys;
    }

    private function getValues(): array {
        $arr_keys_final = [];
        $arr_keys = $this->attributes;
        foreach($arr_keys as $key=>$val) { //to get all fields except the id
            if($key === 0) continue;
            $arr_keys_final[] = $val;
        }
        $arr_values = [];
        foreach($arr_keys_final as $key) {
            $arr_values[] = $this->getAttribute($key);
        }

        return $arr_values;
    }

    private function getSqlValueKeys(): string {
        $arr_keys = $this->attributes;
        if(static::$useTimestamps === true) {
            $arr_keys = array_merge($arr_keys, array_keys($this->timestamps));
        }
        $keys = "";
        foreach($arr_keys as $key=>$val) { //to get all fields except the id
            if($val == $this->primaryKey) continue;
            $keys = $keys . ':' . $val . ',';
        }
        $keys = substr($keys, 0, -1); //micemo zadnji zarez
        return $keys;
    }

    public function save(): int {
        try {
            $conn = Connection::getInstance()->getConnection();
            $keys = $this->getKeys();

            $values = $this->getValues();

            if(static::$useTimestamps === true) {
                $now = Carbon::now()->toDateTimeString();
                $this->setTimeStamps($now, $now);
                $values = array_merge(
                    $values,
                    [
                        $this->timestamps['created_at'],
                        $this->timestamps['updated_at'],
                        $this->timestamps['deleted_at'],
                    ]
                );
            }

            $sqlValueKeys = $this->getSqlValueKeys();
            $t = static::$table;
            $sql = "INSERT INTO $t ($keys) VALUES ($sqlValueKeys)";
            $statement = $conn->prepare($sql);

            $sqlValueKeysArray = explode(',', $sqlValueKeys);
            $payload = [];

            for($i = 0; $i<count($sqlValueKeysArray); $i++){
                $payload[$sqlValueKeysArray[$i]] = $values[$i];
            }

            $statement->execute($payload);

            $pk = $conn->lastInsertId(); //returns the id of the last object inserted
            return $pk;
        }catch(Exception $e) {
            throw $e;
        }
    }

    //update function on a specific model
    public function update(): void {
        if(!$this->getPrimaryKey()) {
            throw new NonExistantModelException();
        }
        try {
            $conn = Connection::getInstance()->getConnection();
            $t = static::$table;
            $keys = explode(',', $this->getKeys(false));
            $sqlValueKeys = explode(',', $this->getSqlValueKeys());
            $keyValuePairString = "";
    
            for($i = 0; $i<count($sqlValueKeys); $i++) {
                $keyValuePairString = $keyValuePairString . $keys[$i] . ' = ' . $sqlValueKeys[$i] . ',';
            }
            $keyValuePairString = substr($keyValuePairString, 0, -1);
            $pk = $this->getPrimaryKey();
            $sql = "UPDATE $t
                    SET $keyValuePairString
                    WHERE $this->primaryKey = $pk
                    ";
            $payload = [];
            if(static::$useTimestamps === true) {
                $this->setTimeStamps(null, Carbon::now()->toDateTimeString(), null);
            }
            foreach($keys as $key) {
                if(in_array($key, array_keys($this->timestamps))) {
                    $payload[$key] = $this->timestamps[$key];
                }
                else {
                    $payload[$key] = $this->getAttribute($key);
                }
            }
    
            $statement = $conn->prepare($sql);
            $statement->execute($payload);
        }catch(Exception $e) {
            throw $e;
        }
    }

    private static function softDelete(mixed $pk): void {
        try {
            $conn = Connection::getInstance()->getConnection();
            $obj = new static();
            $t = static::$table;
            $time = Carbon::now()->toDateTimeString();
            $sql = "UPDATE $t
                    SET deleted_at = '$time'
                    WHERE $obj->primaryKey = $pk
                    ";
            $conn->exec($sql);
        }catch(Exception $e) {
            throw $e;
        }
    }
    private static function purge(mixed $pk): void {
        try {

            $conn = Connection::getInstance()->getConnection();
            $obj = new static();
            $t = static::$table;
            $sql = "DELETE FROM $t WHERE $obj->primaryKey = $pk";
            $statement = $conn->prepare($sql);
            $statement->execute();
        }catch(Exception $e){
            throw $e;
        }
    }

    //static delete functions
    public static function deleteWithPk(mixed $pk): void {
        static::softDelete($pk);
    }

    public static function forceDeleteWithPk(mixed $pk): void {
        static::purge($pk);
    }
    

    //delete function for a model instance
    public function delete(): void {
        if(!$this->getPrimaryKey()) {
            throw new NonExistantModelException();
        }
        self::softDelete($this->getPrimaryKey());
    }

    public function forceDelete(): void {
        if(!$this->getPrimaryKey()) {
            throw new NonExistantModelException();
        }
        static::purge($this->getPrimaryKey());
    }
    //
    public static function all(): array {
        //This attribute is used to get only a single copy of the data
        //and not both by a numerical id and a string id
        $conn = Connection::getInstance()->getConnection();
        $t = static::$table;
        $sql = "SELECT * FROM $t";

        if(static::$useTimestamps === true) {
            $sql = self::excludeDeletes($sql);
        }

        $statement = $conn->prepare($sql);
        $statement->execute();
        $data = $statement->fetchAll();
        if(!$data) {
            return null;
        }
        foreach($data as $obj_arr) {
            $obj = new static();
            $keys = explode(',', $obj->getKeys(true));
            foreach($keys as $key) {
                if(!in_array($key, array_keys($obj->timestamps)) && $key !== $obj->primaryKey) {
                    $obj->setAttribute($key, $obj_arr[$key]);
                }
            }
            $obj->setPrimaryKey($obj_arr[$obj->primaryKey]);
            $obj->setTimeStamps($obj_arr['created_at'], $obj_arr['updated_at'], $obj_arr['deleted_at']);
            $return_data[] = $obj;
        }
        return $return_data;
    }

        /*
            Car::find(1);
            late static binding
        */

    public static function find(int $pk): Model {
        $conn = Connection::getInstance()->getConnection();
        $t = static::$table;
        $obj = new static();
        $sql = "SELECT * FROM $t WHERE $obj->primaryKey = $pk";
        if(static::$useTimestamps === true) {
            $sql = self::excludeDeletes($sql);
        }
        $statement = $conn->prepare($sql);
        $statement->execute();
        $data = $statement->fetch();

        if(!$data) {
            return null;
        }

        $obj = new static();
        $keys = explode(',', $obj->getKeys(true)); //ako na getKeys stavim true vratit ce mi i id
        foreach($keys as $key) {
            if(!in_array($key, array_keys($obj->timestamps)) && $key !== $obj->primaryKey) {
                $obj->setAttribute($key, $data[$key]);
            }
        }
        $obj->setPrimaryKey($data[$obj->primaryKey]);
        $obj->setTimeStamps($data['created_at'], $data['updated_at'], $data['deleted_at']);
        return $obj;
        
    }

    public static function where(string $propertyName, mixed $propertyValue): array {
        $conn = Connection::getInstance()->getConnection();
        $t = static::$table;
        $sql = "SELECT * 
                FROM $t 
                WHERE $propertyName LIKE '$propertyValue'
                ";

        if(static::$useTimestamps === true) {
            $sql = self::excludeDeletes($sql);
        }

        $statement = $conn->prepare($sql);
        $statement->execute();
        $data = $statement->fetchAll();
        if(!$data) {
            return null;
        }
        $return_data = [];
        foreach($data as $obj_arr) {
            $obj = new static();
            $keys = explode(',', $obj->getKeys());
            foreach($keys as $key) {
                if($key !== $obj->primaryKey) {
                    $obj->setAttribute($key, $obj_arr[$key]);
                }
                $obj->setPrimaryKey($obj_arr[$obj->primaryKey]);
                
            }
            $return_data[] = $obj;
        }

        return $return_data;
    }

}