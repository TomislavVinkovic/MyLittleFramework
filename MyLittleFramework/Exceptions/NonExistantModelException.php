<?php

namespace MyLittleFramework\Exceptions;

require __DIR__ . '/../../vendor/autoload.php';

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