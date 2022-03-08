<?php

namespace MyLittleFramework\DB;

abstract class Migration {

    protected int $orderNumber; 

    public final function getMigrationNumber() {
        return $this->orderNumber;
    }

    protected abstract function up();
    protected abstract function down();

}