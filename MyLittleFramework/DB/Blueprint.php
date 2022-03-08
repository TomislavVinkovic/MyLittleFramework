<?php

namespace MyLittleFramework\DB;

use MyLittleFramework\DB\Columns\Column;
use MyLittleFramework\DB\Columns\VarChar;
use MyLittleFramework\DB\Columns\ForeignKey;

final class Blueprint{
    protected array $foreignKeys;
    protected array $columns;
    protected string $table;

    public function __construct(string $table) {
        $this->table = $table;
        $this->columns = [];
        $this->foreignKeys = [];
    }

    public function getColumns() {
        return $this->columns;
    }

    public function getForeignKeys() {
        return $this->foreignKeys;
    }

    public function &bigInteger(string $name): Column {
        $this->newColumn($name, 'BIGINT');
        return end($this->columns);
    }

    public function &text(string $name): Column {
        $this->newColumn($name, 'TEXT');
        return end($this->columns);
    }

    //planning to support nvarchars
    public function &varchar($name, $length): VarChar {
        $col =$this->newVarChar($name, $length);
        return end($this->columns);
    }

    public function &datetime(string $name): Column {
        $this->newColumn($name, 'datetime');
        return end($this->columns);
    }

    public function &float(string $name): Column {
        $this->newColumn($name, 'float');
        return end($this->columns);
    }

    public function &decimal(string $name): Column {
        $this->newColumn($name, 'decimal');
        return end($this->columns);
    }

    public function &increments(string $name): Column {
        $col = new Column($name);
        $col->increments();
        $this->columns[] = $col;
        return end($this->columns);
    }

    public function timestamps(): void {
        $this->newColumn('created_at', 'datetime');
        $this->newColumn('updated_at', 'datetime');
        $this->newColumn('deleted_at', 'datetime');
    }

    public function foreignKey(string $column_name, string $references, $on, $onDelete = 'CASCADE'): void {
        $exists = false;
        foreach($this->columns as $col) {
            if($col->getName() === $column_name) {
                $exists = true;
                break;
            }
        }
        if($exists) {
            $fk = new ForeignKey(
                $column_name,
                $references,
                $on,
                $onDelete
            );
            $this->foreignKeys[] = $fk;
        }
        else {
            throw new Exception("No such column in table $this->table");
        }
    }

    private function newVarChar($name, $length) {
        $column = new VarChar($name, $length);
        $this->columns[] = $column;
    }

    private function newColumn($name, $dataType) {
        $column = new Column($name, $dataType);
        $this->columns[] = $column;
    }

}