<?php

namespace Tienda\App\Controllers;

use Tienda\App\Libs\Controller;

class DocumentController extends Controller
{

    public function login()
    {
        $this->render('index');
    }

    public function register()
    {
        $this->render('login');
    }
}
