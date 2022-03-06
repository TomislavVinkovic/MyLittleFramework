<?php

namespace MyLittleFramework\Exceptions;

require __DIR__ . '/../../vendor/autoload.php';

use Exception;
use Throwable;

class NonExistantPropertyException extends Exception {
    public function __construct($propertyName, $className, int $code = 500, Throwable $previous = null) {
        $message = "No such property $propertyName on class $className";
        parent::__construct($message, $code, $previous);
    }
}