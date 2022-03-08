<?php

namespace MyLittleFramework\Exceptions;

use Exception;
use Throwable;

class NonExistantModelException extends Exception {
    public function __construct(
        string $message = "Cannot update an object that does not exist in the database",
        int $code = 500, 
        Throwable $previous = null) {

        parent::__construct($message, $code, $previous);
    }
}