<?php

namespace MyLittleFramework\DB\Columns;

require __DIR__ . '/../../../vendor/autoload.php';

use MyLittleFramework\DB\Columns\Column;

class VarChar extends Column {
    public function __construct(string $name = "", int $length) {
        parent::__construct($name, 'VARCHAR');
        $this->length = $length;
    }
}