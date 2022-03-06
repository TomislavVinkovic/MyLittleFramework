<?php

namespace App\Controllers;

require __DIR__ . '/../../vendor/autoload.php';

use MyLittleFramework\Model\Model;
use MyLittleFramework\Controller\Controller;
use MyLittleFramework\Controller\Request;
use MyLittleFramework\Responses\Response;

use App\Models\Car;
use PDO;
use Exception;

class CarController extends Controller{

    //GET methods
    public function all() {
        var_dump(Car::all());
    }

    public function show(Request $r) {
        $id = $r->GET()['id'];
        $car = Car::find($id);
        if($car === null) {
            Response::redirect('/404NotFound');
        }
        else {
            require_once(__DIR__ . '/../templates/car.php');
        }
    }

    public function filter(Request $r) { //na ovu metodu moram dodati support za vise argumenata
        throw new Exception('Not implemented yet');
    }

    public function update(Request $r) {
        $id = $r->get()['id'];
        $car = Car::find($id);

        if($car === null) {
            require_once(__DIR__ . '/../templates/404NotFound.php');
        }
        else {
            require_once(__DIR__ . '/../templates/updateCar.php');
        }
    }

    public function new() {
        require_once(__DIR__ . '/../templates/newCar.php');
    }

    //POST, PATCH and DELETE methods
    public function store(Request $r) {
        

        $data = $r->POST();

        $car = new Car();
        $car->brand = $data['brand'];
        $car->model = $data['model'];
        $car->color = $data['color'];
        $car->car_weight = $data['car_weight'];
        $car->top_speed = $data['top_speed'];
        $car->country_of_origin = $data['country_of_origin'];
        

        $id = $car->save();
        Response::redirect("car?id=$id"); //redirects are not working
        exit();
    }

    public function patch(Request $r) {
        try {
            if(!$r->post()['id']) {
                Response::redirect('/404NotFound');
            }
            $data = $r->post();
            /*
            $car = Car::find($data['id']);
            
            if($car === null) {
                Response::redirect('/404NotFound');
            }
            */
            $id = $data['id'];
            $car = new Car();
            $car->setPrimaryKey($id);
            $car->brand = $data['brand'];
            $car->model = $data['model'];
            $car->color = $data['color'];
            $car->car_weight = $data['car_weight'];
            $car->top_speed = $data['top_speed'];
            $car->country_of_origin = $data['country_of_origin'];

            $car->update();

            Response::redirect("/car?id=$id");
        }catch(Exception $e) {
            throw $e;
        }
        
    }

    public function delete(Request $r) {
        $data = $r->get();
        if($data['id'] === null) {
            Response::redirect('/404NotFound');
        }
        else {
            try {
                $id = $data['id'];
                Car::deleteWithPk($id);

                Response::redirect("/");
            }catch(Exception $e) {
                throw $e;
            }
        }
    }
}