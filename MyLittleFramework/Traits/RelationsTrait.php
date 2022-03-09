<?php

namespace MyLittleFramework\Traits;

use MyLittleFramework\DB\Connection;
use Exception;
use Carbon\Carbon;

trait RelationsTrait {

    public function hasOne(string $localKey, string $foreignKey, string $foreignClass): object {
        //I only implemented the has one relationship because of the simplicity of my project

        try {
            $obj = new $foreignClass;
            $t = $obj->getTable();
            $conn = Connection::getInstance()->getConnection();
            $localKeyVal = $this->getAttribute($localKey);
            $sql = "SELECT * FROM $t WHERE $foreignKey = $localKeyVal";
            $statement = $conn->prepare($sql);
            $statement->execute();
            $data = $statement->fetch();
            $keys = explode(',', $obj->getKeys(true));
            foreach($keys as $key) {
                if(!in_array($key, array_keys($obj->timestamps)) && $key !== $obj->primaryKey) {
                    $obj->setAttribute($key, $data[$key]);
                }
            }
            $obj->setPrimaryKey($data[$obj->primaryKey]);
            $obj->setTimeStamps($data['created_at'], $data['updated_at'], $data['deleted_at']);

            return $obj;
        }catch(Exception $e) {
            throw $e;
        }
    }

    //za pokazni primjer radit ce samo za hasOne relationship, i za samo jedan objekt
    public function with(string $localMethod): ?object {
        try {
            if($this === null) {
                return null;
            }
            $callback = [$this, $localMethod];
            $result = call_user_func($callback);
            $this->$localMethod = $result;
            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }

}