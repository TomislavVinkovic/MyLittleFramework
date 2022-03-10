<?php

namespace App;

require __DIR__ . '/../vendor/autoload.php';

use MyLittleFramework\Router\Router;
use App\Controllers\CarController;
use App\Controllers\HomeController;
use MyLittleFramework\Views\ViewTrait;
use App\Models\Car;

class Routes {
    private Router $router;

    use ViewTrait;

    public function __construct() {
        $this->router = new Router;
        
        //define your routes down below:

        $this->router->get('/', HomeController::class . '::index');

        $this->router->get('/car', CarController::class . '::show');
        $this->router->get('/cars', CarController::class . '::all');
        $this->router->get('/cars/filter', CarController::class . '::filter');
        $this->router->get('/newCar', CarController::class . '::new');
        $this->router->get('/updateCar', CarController::class . '::update');

        $this->router->patch('/patchCar', CarController::class . '::patch');
        $this->router->post('/storeCar', CarController::class . '::store');
        $this->router->delete('/deleteCar', CarController::class . '::delete');

        $this->router->get('/404NotFound', $this->router->getNotFoundHandler());

        $this->router->run();
    }
}