<?php

namespace MyLittleFramework\DB;

require __DIR__ . '/../../vendor/autoload.php';

abstract class Migration {

    protected abstract function up();
    protected abstract function down();

}