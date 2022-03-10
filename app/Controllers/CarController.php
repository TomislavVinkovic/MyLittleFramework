<?php

namespace App\Controllers;

use MyLittleFramework\Model\Model;
use MyLittleFramework\Controller\Controller;
use MyLittleFramework\Requests\Request;
use MyLittleFramework\Responses\Response;
use MyLittleFramework\Query\Query;

use App\Models\Car;
use Exception;

class CarController extends Controller{
    protected $notFoundPage = '404NotFound.html.twig';

    public function all() {
        $cars = Car::all();
        $this->render(
            'cars',
            [
                'cars' => $cars
            ]
        );
    }

    public function show(Request $r) {
        $id = $r->GET()['id'];
        $car = Car::find($id)?->with('engine');
        if(!$car) {
            $this->notFound();
        }
        $this->render(
            'car',
            [
                'car' => $car
            ]
        );            
    }

    public function filter(Request $r) {
        $filters = $r->GET();

        //napravio sam sve sa LIKE operatorom radi jednostavnosti. Svjestan sam da bi se npr, u API callu, u post requestu lako mogao dodati operator
        //na kraju krajeva, kad bi netko koristio moj framework, on bi mogao napraviti isto :)
        //$cars = Car::where('brand', 'LIKE', 'VW')?->orWhere('color', 'like', 'black')?->get(); //radi i ovakav neki primjer

        $q = new Query(Car::class);
        
        foreach($filters as $key=>$value) {
            $q->where($key, 'LIKE', $value); //ako postoji vise od jednog where querya, query automatski izmedu svakog stavlja and operator
        }

        $cars = $q->get();

        $this->render(
            'cars',
             [
                 'cars' => $cars
             ]
        );
    }

    public function update(Request $r) {
        $id = $r->get()['id'];
        if(!$id) {
            $this->notFound();
        }

        $car = Car::find($id);

        if(!$car) {
            $this->notFound();
        }
        else {
            $this->render(
                'updateCar',
                [
                    'car' => $car
                ]
            );
        }
    }

    public function new() {
        $this->render('newCar');
    }

    //POST, PATCH and DELETE methods
    public function store(Request $r) {
        

        $data = $r->POST();

        $car = new Car();
        $car->engine_id = 2;
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