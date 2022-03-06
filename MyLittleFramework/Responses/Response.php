<?php

namespace MyLittleFramework\Responses;

class Response {

    protected const OK = 200;
    protected const CREATED = 201;
    protected const NOT_FOUND = 404;
    protected const INTERNAL_SERVER_ERROR = 500;

    protected string $message;
    protected int $responseCode;
    protected string $error;
    protected string $redirectURL;

    public function __construct($responseCode = 200, $message = null, $error = null) {
        $this->message = $message;
        $this->responseCode = $responseCode;
        $this->error = $error;
        $this->redirectURL = null;
    }

    public function getMessage(): ?string {
        return $this->message;
    }

    public function getResponseCode(): ?int {
        return $this->responseCode;
    }

    public static function redirect(string $url): void {
        header("Location: $url");   
    }
}