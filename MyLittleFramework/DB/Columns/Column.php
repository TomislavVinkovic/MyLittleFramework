<?php

namespace MyLittleFramework\DB\Columns;

require __DIR__ . '/../../../vendor/autoload.php';

class Column {
    protected bool $primaryKey;
    protected bool $increments;
    protected bool $nullable;

    protected string $name;
    protected string $datatype;
    protected int $length;

    public function __construct($name = "", $datatype = "", bool $nullable = true, bool $pk = false, bool $inc = false) {
        $this->name = $name;
        $this->datatype = $datatype;
        $this->nullable = $nullable;
        $this->primaryKey = $pk;
        $this->increments = $inc;
    }

    //public function __toArray() {}

    //A bunch of getters and setters
    public function setDataType(string $datatype): void {
        //TODO: add a list of supported types
        $this->datatype = $datatype;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getDataType(): string {
        return $this->datatype;
    }

    public function getName(): string {
        return $this->name;
    }

    public function isPrimaryKey(): string {
        if($this->primaryKey) return 'PRIMARY KEY';
        else return '';
    }

    public function isNullable(): string {
        if($this->nullable) return 'NULL';
        else return '';
    }

    public function isAutoIncrement() {
        if($this->increments) return 'AUTOINCREMENT';
        else return '';
    }
    // -------------------------------------------------

    public function primaryKey(): void {
        $this->nullable = false;
        $this->primaryKey = true;
    }

    public function references($table): void {
        $this->primaryKey = false;
        $this->foreignKey['foreignKey'] = true;
        $this->foreignKey['table'] = true;
    }

    public function nullable($nullable = true): void {
        $this->nullable = $nullable;
    }

    public function increments(): void {
        $this->datatype = 'INTEGER';
        $this->increments = true;
        $this->primaryKey();
    }
}