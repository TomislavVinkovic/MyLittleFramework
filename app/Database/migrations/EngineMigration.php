<?php

namespace App\Database\Migrations;

use MyLittleFramework\DB\Migration;
use MyLittleFramework\DB\Blueprint;
use MyLittleFramework\DB\Schema;


class EngineMigration extends Migration {

    protected int $orderNumber = 2;

    public function up() {
        Schema::create('engines', function(Blueprint $table){
            $table->increments('id');
            $table->bigInteger('hp')->nullable(false);
            $table->varchar('unique_number', 150)->nullable(false);
            $table->float('cubicPower')->nullable(false);

            $table->timestamps();

            return $table; //do not delete this line
        });
    }

    public function down() {
        Schema::dropIfExists('engines');
    }


}