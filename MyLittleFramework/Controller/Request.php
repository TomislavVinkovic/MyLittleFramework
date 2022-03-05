<?php

namespace MyLittleFramework\Controller;

class Request {
    private array $GET;
    private array $POST;
    private string $METHOD;

    public function __construct(string $method, array $get, array $post) {
        $this->GET = $get;
        $this->POST = $post;
        $this->METHOD = $method;
    }

    public function GET(): array {
        return $this->GET;
    }

    public function POST(): array {
        return $this->POST;
    }

    public function METHOD(): array {
        return $this->METHOD;
    }
}