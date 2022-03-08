<?php

namespace MyLittleFramework\DB\Columns;

use MyLittleFramework\DB\Columns\Column;

class VarChar extends Column {

    private int $length;

    public function __construct(string $name, int $length = 255) {
        parent::__construct($name, 'VARCHAR');
        $this->length = $length;
    }
}