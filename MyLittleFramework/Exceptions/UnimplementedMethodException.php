<?php

namespace MyLittleFramework\Exceptions;

use Exception;
use Throwable;

class UnimplementedMethodException extends Exception {
    public function __construct(string $method, Throwable $previous = null) {
        $message = "Method $method is not implemented yet";
        parent::__construct($message, 500, $previous);
    }
}