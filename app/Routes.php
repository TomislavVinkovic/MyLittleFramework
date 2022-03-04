<?php

namespace App;

require __DIR__ . '/../vendor/autoload.php';

use MyLittleFramework\Router\Router;
use App\Controllers\CarController;

class Routes {
    private Router $router;

    public function __construct() {
        $this->router = new Router;
        
        //define your routes down below:
        
        $this->router->setNotFoundHandler(function() {
            //this view handling is just for demonstration purposes
            $title = "Not found!"; //an example variable that we can pass
            require_once(__DIR__ . '/templates/404NotFound.php');
        });

        $this->router->get('/', function() {
            echo 'Home Page';
        });

        $this->router->get('/car', CarController::class . '::show');
        $this->router->get('/car/filter', CarController::class . '::filter');

        $this->router->get('/newCar', CarController::class . '::new');
        $this->router->post('/storeCar', CarController::class . '::store');

        //TO BE ADDED: UPDATE, DELETE, FORCEDELETE

        $this->router->run();
    }
}