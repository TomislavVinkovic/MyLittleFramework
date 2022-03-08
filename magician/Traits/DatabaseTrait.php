<?php

namespace Magician\Traits;

use HaydenPierce\ClassFinder\ClassFinder;
use MyLittleFramework\DB\Migration;

trait DatabaseTrait {
    private function cmpMigrations(string $m1, string $m2) {
        try {
            $obj1 = new $m1;
            $obj2 = new $m2;
            if($obj1->getMigrationNumber() === $obj2->getMigrationNumber()) {
                throw new Exception("Two migrations cannot have the same order number");
                die;
            }
            else if($obj1->getMigrationNumber() > $obj2->getMigrationNumber()) {
                return 1;
            }
            else {
                return -1;
            }
        }catch(Exception $e) {
            throw $e;
        }
    }

    private function getOrderedMigrations(bool $reverse = false): array {
        $classes = ClassFinder::getClassesInNamespace('App\Database\Migrations');
        usort($classes, 'self::cmpMigrations');

        if(!$reverse) {
            return $classes;
        }
        else {
            return array_reverse($classes);
        }
    }

    public function migrate() {
        if(count($this->arguments) === 0) {
            try {
                $classes = $this->getOrderedMigrations();
                foreach($classes as $class) {
                    $obj = new $class;
                    $obj->up();
                    print "Migration $class successfully migrated\n";
                }
            }catch(Exception $e) {
                throw $e;
            }
        }

        else {
            try {
                $migrationName = $this->arguments[0];
                $class = "App\Database\Migrations\\$migrationName";
                $obj = new $class;
                $obj->up();
            } catch (Exception $th) {
                throw $e;
            }
        }
    }

    public function resetMigrations() {
        try {
            $classes = $this->getOrderedMigrations(true);
            foreach($classes as $class) {
                $obj = new $class;
                $obj->down();
                print "$class rolled back \n";
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function rollBack() { 
        if(count($this->arguments) === 0) {
            throw new Exception("You have to name a migration to roll back");
            die;
        }
        try {
            $migrationName = $this->arguments[0];
            $class = "App\Database\Migrations\\$migrationName";
            $obj = new $class;
            $obj->down();
        } catch (Exception $th) {
            throw $e;
        }
    }
}