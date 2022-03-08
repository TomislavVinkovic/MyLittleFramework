<?php

namespace Magician;

use Magician\Traits\DatabaseTrait;

class Magician {

    private array $arguements;

    use DatabaseTrait;

    public function __construct(array $arguments) {
        $method = $arguments[1]; //ime metode koju moramo pozvati je na indexu 1

        //micemo magician.php i ime metode iz argumenata
        array_shift($arguments);
        array_shift($arguments);

        $this->arguments = $arguments;
        $callback = [$this, $method];

        call_user_func($callback);
    }

    public function help() {
        $helpText = "\n
                    help
                    migrate <className> -> migrate all migrations to the database if className is empty. Otherwise, migrate only the specified migration.\n
                    resetMigrations -> reset all migrations.\n
                    rollBack <className> -> roll back a specific migration";
        print($helpText);
    }

}