<?php

namespace App\Controllers;

use MyLittleFramework\Controller\Controller;

class HomeController extends Controller {
    public function index() {
        $this->render('home');
    }
}