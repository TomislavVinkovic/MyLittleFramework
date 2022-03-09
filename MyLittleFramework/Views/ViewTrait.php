<?php

namespace MyLittleFramework\Views;


use MyLittleFramework\Views\ViewService;

trait ViewTrait {
    protected $viewManager;
    protected $notFound;

    public function __construct() {
        $this->viewManager = ViewService::getInstance()->getTwigInstance();
    }

    protected function render(string $template, array $args) {
        if(str_contains($template, 'html.twig')) {
            echo $this->viewManager->render($template, $args);
        }
        else if(str_contains($template, 'html')) {
            echo $this->viewManager->render($template . '.twig', $args);
        }
        else {
            echo $this->viewManager->render($template . '.html.twig', $args);
        }
    }

    protected function notFound() {
        echo $this->viewManager->render($this->notFoundPage);
        die;
    }
}