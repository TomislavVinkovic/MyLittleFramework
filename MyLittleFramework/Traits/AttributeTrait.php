<?php

namespace MyLittleFramework\Traits;

use Exception;

trait AttributeTrait {
    protected $attributes;
    protected string $primaryKey;
    protected $attribute_values;
    protected $allowed;

    private function primaryKey_isset(): void { //updateati za array keyeva
        if(!$this->primaryKey) {
            $className = get_called_class();
            throw new Exception("The primary key is not set on model $className");
        }
    }

    public function setPrimaryKey(mixed $value): void {
        $this->primaryKey_isset();
        try {
            $this->attribute_values[$this->primaryKey] = $value;
        }catch(Exception $e) {
            throw $e->getMessage();
        }
    }

    public function getPrimaryKey(): mixed {
        return $this->attribute_values[$this->primaryKey];
    } 

    protected function getAttribute(string $propertyName): mixed {
        $this->primaryKey_isset();
        if (!in_array($propertyName, $this->attributes)) {
            $class = get_called_class();
            throw new Exception("No such property on $class");
        }
        return $this->attribute_values[$propertyName];
    }

    protected function setAttribute(string $propertyName, mixed $propertyValue): void {

        if (!in_array($propertyName, $this->attributes)) {
            $class = get_called_class();
            throw new Exception("No such property on $class");
        }

        if (!in_array($propertyName, $this->allowed)) {
            throw new Exception("The property $propertyName is not allowed to be modified.");
            return;
        }
        $this->attribute_values[$propertyName] = $propertyValue;
    }
}