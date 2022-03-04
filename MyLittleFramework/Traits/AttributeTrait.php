<?php

namespace MyLittleFramework\Traits;

require __DIR__ . '/../../vendor/autoload.php';

trait AttributeTrait {
    protected $attributes;
    protected $allowed;


    protected function getAttribute($propertyName) {
        if (!array_key_exists($propertyName, $this->attributes)) {
            echo "No such property on Model";
            return null;
        }
        return $this->attributes[$propertyName];
    }

    protected function setAttribute($propertyName, $propertyValue) {
        if (!array_key_exists($propertyName, $this->attributes)) {
            echo "No such property on Model";
            return;
        }
        if (!in_array($propertyName, $this->allowed)) {
            echo "The property $propertyName is not allowed to be modified.";
            return;
        }
        $this->attributes[$propertyName] = $propertyValue;
    }
}