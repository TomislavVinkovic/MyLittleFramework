<?php

namespace MyLittleFramework\Model;

require __DIR__ . '/../../vendor/autoload.php';

use MyLittleFramework\Traits\AttributeTrait;
use MyLittleFramework\Traits\TimestampTrait;
use Carbon\Carbon;
use PDO;

abstract class Model {

    use AttributeTrait;
    use TimestampTrait;

    protected static $table;
    protected static $useTimestamps;
    
    public function __get($propertyName): mixed {
        return $this->getAttribute($propertyName);
    }

        
    public function __set($propertyName, $propertyValue): void {
        $this->setAttribute($propertyName, $propertyValue);
    }

    public function __call($method, $arguments) {
        throw new Exception('Not implemented yet');
    }


    public function toArray() {
        throw new Exception('Not implemented yet');
    }

    public function __toString() {
        throw new Exception('Not implemented yet');
    }

    public function __sleep() {
        return $attributes->array_keys();
    }

    public function __wakeup() {
        throw new Exception('Not implemented yet');
    }


    public function __isset($propertyName): bool {
        try {
            return (isset($this->attributes['propertyName']));
        }
        catch(Exception $e){
            $className = get_class($this);
            echo "No such property $propertyName on class $className";
        }
    }

    public function __unset($propertyName): void {
        try {
            unset($this->attributes['propertyName']);
        }
        catch(Exception $e){
            $className = get_class($this);
            throw new Exception("No such property $propertyName on class $className"); //ovo je dobar primjer gdje mogu implementirati vlastiti exception
        }
        
    }

    private function getKeys($id = false): string {
        $arr_keys = array_keys($this->attributes);
        if(static::$useTimestamps === true) {
            $arr_keys = array_merge($arr_keys, array_keys($this->timestamps));
        }
        $keys = "";
        foreach($arr_keys as $key=>$val) { //to get all fields except the id
            if($key === 0 && $id === false) continue;
            $keys = $keys . $val . ',';
        }
        $keys = substr($keys, 0, -1); //micemo zadnji zarez
        return $keys;
    }

    private function getValues(): array {
        $arr_keys_final = [];
        $arr_keys = array_keys($this->attributes);
        foreach($arr_keys as $key=>$val) { //to get all fields except the id
            if($key === 0) continue;
            $arr_keys_final[] = $val;
        }
        $arr_values = [];
        foreach($arr_keys_final as $key) {
            $arr_values[] = $this->attributes[$key];
        }

        return $arr_values;
    }

    private function getSqlValueKeys(): string {
        $arr_keys = array_keys($this->attributes);
        if(static::$useTimestamps === true) {
            $arr_keys = array_merge($arr_keys, array_keys($this->timestamps));
        }
        $keys = "";
        foreach($arr_keys as $key=>$val) { //to get all fields except the id
            if($key === 0) continue;
            $keys = $keys . ':' . $val . ',';
        }
        $keys = substr($keys, 0, -1); //micemo zadnji zarez
        return $keys;
    }

    public abstract static function createTable($connection);

    public function save($conn): int {
        try {
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

            $id = $conn->lastInsertId(); //returns the id of the last object inserted
            return $id;
        }catch(Exception $e) {
            throw $e;
        }
    }

    //update function on a specific model
    public function update($conn): void {
        if($this->id === null) {
            throw new Exception("Cannot update an object that does not exist in the database"); //jos jedno mjesto za neki custom exception
        }
        try {
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $t = static::$table;
            $keys = explode(',', $this->getKeys(false));
            $sqlValueKeys = explode(',', $this->getSqlValueKeys());
            $keyValuePairString = "";
    
            for($i = 0; $i<count($sqlValueKeys); $i++) {
                $keyValuePairString = $keyValuePairString . $keys[$i] . ' = ' . $sqlValueKeys[$i] . ',';
            }
            $keyValuePairString = substr($keyValuePairString, 0, -1);
            $id = $this->attributes['id'];
            $sql = "UPDATE $t
                    SET $keyValuePairString
                    WHERE id = $id
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
                    $payload[$key] = $this->attributes[$key];
                }
            }
    
            $statement = $conn->prepare($sql);
            $statement->execute($payload);
        }catch(Exception $e) {
            throw $e;
        }
    }

    private static function softDelete($conn, $id): void {
        try {
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $t = static::$table;
            $time = Carbon::now()->toDateTimeString();
            $sql = "UPDATE $t
                    SET deleted_at = $time
                    WHERE id = $id
                    ";
            $conn->exec($sql);
        }catch(Exception $e) {
            throw $e;
        }
    }
    private function purge($conn, $id) {
        try {
            $t = static::$table;
            $sql = "DELETE FROM $t WHERE id = $id";
            $statement = $conn->prepare($sql);
            $statement->execute();
        }catch(Exception $e){
            throw $e;
        }
    }

    //static delete functions
    public static function deleteWithId($conn, $id) {
        static::softDelete($conn, $id);
    }

    public static function forceDeleteWithId($conn, $id): void {
        static::purge($conn, $id);
    }
    

    //delete function for a model instance
    public function delete($conn): void {
        if($this->id === null) {
            throw new Exception("Cannot delete an object that does not exist in the database"); //another good place for my custom exception
        }
        self::softDelete($conn, $id);
    }

    public function forceDelete($conn): void {
        if($this->id === null) {
            throw new Exception("Cannot delete an object that does not exist in the database"); //another good place for my custom exception
        }
        $id = $this->id;
        static::purge($conn, $id);
    }
    //
    public static function all($conn) {
        //This attribute is used to get only a single copy of the data
        //and not both by a numerical id and a string id
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
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
                if(!in_array($key, array_keys($obj->timestamps))) {
                    $obj->attributes[$key] = $obj_arr[$key];
                }
            }
            $obj->setTimeStamps($obj_arr['created_at'], $obj_arr['updated_at'], $obj_arr['deleted_at']);
            $return_data[] = $obj;
        }
        return $return_data;
    }

        /*
            Car::find(1);
            late static binding
        */

    public static function find($conn, $id) {
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $t = static::$table;
        $sql = "SELECT * FROM $t WHERE id = $id";
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
            if(!in_array($key, array_keys($obj->timestamps))) {
                $obj->attributes[$key] = $data[$key];
            }
        }
        $obj->setTimeStamps($data['created_at'], $data['updated_at'], $data['deleted_at']);
        return $obj;
        
    }

    public static function where($conn, $propertyName, $propertyValue) {
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
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
                $obj->attributes[$key] = $obj_arr[$key];
            }
            $return_data[] = $obj;
        }

        return $return_data;
    }

}