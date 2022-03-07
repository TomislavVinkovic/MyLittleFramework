<?php

namespace App\Database\Migrations;

require __DIR__ . '/../../../vendor/autoload.php';

use MyLittleFramework\DB\Migration;
use MyLittleFramework\DB\Blueprint;
use MyLittleFramework\DB\Schema;


class CarMigration extends Migration {

    public function up() {
        Schema::create('cars', function(Blueprint $table){
            $table->increments('id');
            $table->bigInteger('engine_id');
            $table->varchar('brand', 100)->nullable(false);
            $table->varchar('model', 100)->nullable(false);
            $table->varchar('color', 100);
            $table->float('car_weight', 100);
            $table->bigInteger('top_speed');
            $table->varchar('chasis_number', 150)->nullable(false);
            $table->varchar('country_of_origin', 100)->nullable(false);

            $table->foreignKey('engine_id', 'id', 'engines');

            $table->timestamps();

            return $table; //do not delete this line
        });
    }

    public function down() {
        Schema::dropIfExists('cars');
    }


}