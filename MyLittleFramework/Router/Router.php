<?php

namespace MyLittleFramework\Router;

require __DIR__ . '/../../vendor/autoload.php';

use MyLittleFramework\Controller\Request;

class Router {

    protected $handlers;

    private const METHOD_GET = 'GET';
    private const METHOD_POST = 'POST';
    private const METHOD_PATCH = 'POST';
    private const METHOD_DELETE = 'GET';
    protected $notFoundHandler;

    public function get($action, $handler) {
        $this->addHandler(self::METHOD_GET, $action, $handler);
    }

    public function post($action, $handler) {
        $this->addHandler(self::METHOD_POST, $action, $handler);
    }

    public function patch($action, $handler) {
        $this->addHandler(self::METHOD_PATCH, $action, $handler);
    }

    public function delete($action, $handler) {
        $this->addHandler(self::METHOD_DELETE, $action, $handler);
    }

    public function setNotFoundHandler($handler) {
        $this->notFoundHandler = $handler;
    }

    public function getNotFoundHandler() {
        return $this->notFoundHandler;
    }

    public function run() {
        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        $requestPath = $requestUri['path'];
        //$requestUri['query'] contains everything after the questionmark
        $method = $_SERVER['REQUEST_METHOD'];

        //a method we will run later
        $callback = null;

        foreach($this->handlers as $handler) {
            if($handler['action'] === $requestPath && $method === $handler['method']) {
                $callback = $handler['handler'];
            }
        }

        //we basicaly use this if we call
        //a controller method
        //EXAMPLE Car::show(not static!)
        if(is_string($callback)) {
            //we explode the callback to get an array
            //where the first element will be the class name
            //and the second element will be the method name
            $parts = explode('::', $callback);

            if(is_array($parts)) {
                $className = array_shift($parts);
                $methodName = array_shift($parts);

                $controller = new $className;
                $callback = [$controller, $methodName]; //pozvat ce se $controller->method
            }
        }

        //if the route is not found, we can provide a
        //callback for the router to use or we can
        //simply rely on the default
        if($callback === null) {
            if(!empty($this->notFoundHandler)) {
                $callback = $this->notFoundHandler;
            }
            else {
                $callback = function() {
                    header('HTTP/1.0 404 not found');
                };
            }
        }
        $request = new Request(
            $method,
            $_GET,
            $_POST
        );

        //calls our callback method with the provided parameters
        call_user_func_array($callback, [
            //array_merge($_GET, $_POST) //$_GET i $_POST su built in u sam php
            $request
        ]);
    }

    private function addHandler($method, $action, $handler) {
        $this->handlers[$method . $action] = [
            'action' => $action,
            'method' => $method,
            'handler' => $handler
        ];
    }
}