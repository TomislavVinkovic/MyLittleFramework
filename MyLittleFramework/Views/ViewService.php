<?php

namespace MyLittleFramework\Views;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class ViewService {

    private static $instance = null;
    private FilesystemLoader $loader;
    private Environment $twig;

    private function __construct() {
        $this->loader = new FilesystemLoader(__DIR__ . '/../../app/templates');
        $this->twig = new Environment($this->loader);
    }

    public static function getInstance(): ViewService {
        if(!self::$instance) {
            self::$instance = new ViewService();
        }
        return self::$instance;
    }

    public function getTwigInstance() {
        return $this->twig;
    }

}