<?php

namespace App\Controllers;

require __DIR__ . '/../../vendor/autoload.php';

use MyLittleFramework\Model\Model;
use MyLittleFramework\Controller\Controller;
use MyLittleFramework\Controller\Request;

use App\Models\Car;
use PDO;

class CarController extends Controller{

    public function all() {
        return Car::all($this->db);
    }

    public function show(Request $r) {
        /*
        $id = $r->GET()['id'];
        return Car::find($this->db, $id);
        */

        var_dump($r);
    }

    public function filter(Request $r) { //na ovu metodu moram dodati support za vise argumenata

        /*
        $name = $r->GET()['name'];
        $val = $r->GET()['val'];
        return Car::where($this->db, $name, $val);
        */

        var_dump($r);
    }

    public function update(Request $r) {
        return 'To be implemented';
    }

    public function new() {
        require_once(__DIR__ . '/../templates/car.php');
    }

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
}