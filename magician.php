<?php

require_once 'vendor/autoload.php'; //here i need the vendor autoload because the magician is not accesedfrom the index file

use Magician\Magician;

error_reporting(E_ALL ^ E_NOTICE);

if(count($argv) === 1) {
    print "To see all available magician commands, type php magician.php help";
    die;
} 
$magician = new Magician($argv);