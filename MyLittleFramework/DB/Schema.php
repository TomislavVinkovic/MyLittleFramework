<?php

namespace MyLittleFramework\DB;

use MyLittleFramework\DB\Connection;
use MyLittleFramework\DB\Blueprint;

class Schema {

    private static function getColumnStatements(&$columns) {
        $statements = [];

        foreach($columns as $col) {
            //this string is to be expanded later
            //remove \r\n
            $n = $col->getName();
            $d = $col->getDataType();
            $p = $col->isPrimaryKey();
            $in = $col->isNullable();
            $inc = $col->isAutoIncrement();
            $statements[] = "$n $d $p $in $inc";
        }

        return $statements;
    }

    public static function create(string $t, callable $callback): void {
        try {
            $blueprint = new Blueprint($t);
            $conn = Connection::getInstance()->getConnection();
            $table = call_user_func_array(
                $callback,
                [
                    &$blueprint,
                ]
            );

            $columns = $table->getColumns();
            $foreignKeys = $table->getForeignKeys();
            $statements = self::getColumnStatements($columns);
            $sql = "CREATE TABLE $t(";
            foreach($statements as $s) {
                if($s !== end($statements)) {
                    $sql = $sql . $s . ',';
                }
                else {
                    $sql = $sql . $s;
                }
            }

            foreach($foreignKeys as $fk) {
                $k = $fk->getColumn();
                $r = $fk->getReferences();
                $on = $fk->getOn();
                $onDelete = $fk->getOnDelete();
                $sql = $sql . ",FOREIGN KEY ($k) REFERENCES $on($r) ON DELETE CASCADE";
            }
            $sql = $sql . ');';
            $conn->exec($sql);
            //
            
        }catch(Exception $e) {
            throw $e;
        }
        
    }

    public static function dropIfExists(string $table): void {
        try {
            $conn = Connection::getInstance()->getConnection();
            $sql = "DROP TABLE IF EXISTS $table";
            $conn->exec($sql);
        }catch(Exception $e) {
            throw $e;
        }
    }

    public static function alter(string $table, callable $callback): void {
        try {
            $blueprint = new Blueprint($t);
            $conn = Connection::getInstance()->getConnection();
            $table = call_user_func_array(
                $callback,
                [
                    $blueprint,
                ]
            );
            var_dump($table);
        }catch(Exception $e) {
            throw $e;
        }
    }
}