<?php

namespace App\Models;

use MyLittleFramework\Model\Model;
use App\Models\Engine;
use Exception;

class Car extends Model {

    protected $allowed = [
        'engine_id','brand', 'model', 'color', 'car_weight', 
        'chasis_number', 'top_speed', 'country_of_origin'
    ];

    protected static $table = 'cars';
    protected string $primaryKey = 'id';
    protected static $useTimestamps = true;

    public function __construct($chasis_number = null) {

        parent::__construct(); //do not delete this line

        if($chasis_number === null) {
            $this->chasis_number = uniqid("FML");
        }
        else {
            $this->chasis_number = $chasis_number;
        }
    }

    public function engine() {
        $engine = $this->hasOne('engine_id', 'id', Engine::class);
        return $engine;
    }
}