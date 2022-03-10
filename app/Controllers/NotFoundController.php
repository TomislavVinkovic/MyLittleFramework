<?php

namespace App\Controllers;

use MyLittleFramework\Controller\Controller;

class NotFoundController extends Controller {
    public function notFound() {
        $this->render('404NotFound');
    }
}