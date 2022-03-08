<?php

namespace App\Models;

use MyLittleFramework\Model\Model;
use Exception;

class Engine extends Model {

    protected $allowed = [
        'hp', 'unique_number', 'cubicPower'
    ];
    protected static $table = 'engines';
    protected string $primaryKey = 'id';
    protected static $useTimestamps = true;
}