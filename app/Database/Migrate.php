<?php

namespace App\Database;

use App\Database\Migrations\CarMigration;
use App\Database\Migrations\EngineMigration;

class Migrate {
    static function up() {
        //I will probably change this later
        $cm = new CarMigration;
        //$em = new EngineMigration;
        $cm->up();
        //$em->up();
    }

    static function down() {
        $cm = new CarMigration;
        $cm->down();
    }
}