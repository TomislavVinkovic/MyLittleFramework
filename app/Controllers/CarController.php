<?php

namespace App\Controllers;

require __DIR__ . '/../../vendor/autoload.php';

use MyLittleFramework\Model\Model;
use MyLittleFramework\Controller\Controller;
use MyLittleFramework\Controller\Request;

use App\Models\Car;
use PDO;

class CarController extends Controller{

    //GET methods
    public function all() {
        var_dump(Car::all($this->db));
    }

    public function show(Request $r) {
        $id = $r->GET()['id'];
        $car = Car::find($this->db, $id);

        require_once(__DIR__ . '/../templates/car.php');
    }

    public function filter(Request $r) { //na ovu metodu moram dodati support za vise argumenata
        throw new Exception('Not implemented yet');
    }

    public function update(Request $r) {
        throw new Exception('Not implemented yet');
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
        

        $id = $car->save($this->db);
        self::redirect("car?id=$id", self::OK); //redirects are not working
        exit();
    }

    public function patch(Request $r) {
        var_dump($r);
    }

    public function delete(Request $r) {
        var_dump($r);
    }
}