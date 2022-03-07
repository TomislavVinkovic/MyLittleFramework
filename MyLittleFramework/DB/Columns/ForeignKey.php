<?php

namespace MyLittleFramework\DB\Columns;

require __DIR__ . '/../../../vendor/autoload.php';

use MyLittleFramework\DB\Columns;

class ForeignKey extends Column {

    public function __construct(string $column_name, string $references, string $on, string $onDelete) {
        $this->column = $column_name;
        $this->references = $references;
        $this->on = $on;
        $this->onDelete = $onDelete;
    }

    private string $column;
    private string $references;
    private string $on;
    private string $onDelete;

    public function getColumn(): string {
        return $this->column;
    }

    public function getReferences(): string {
        return $this->references;
    }

    public function getOn(): string {
        return $this->on;
    }

    public function getOnDelete(): string {
        return $this->onDelete;
    }
}