<?php

namespace Tienda\App\Controllers;

use Tienda\App\Libs\Controller;

class DocumentController extends Controller
{

    public function index()
    {
        $this->render('index');
    }

    public function login()
    {
        $this->render('login');
    }

    public function forgotPassword()
    {
        $this->render('forgotPassword');
    }

    public function register()
    {
        $this->render('register');
    }

    public function editEmail()
    {
        $this->render('editEmail');
    }

    public function editPassword()
    {
        $this->render('editPassword');
    }

    public function editUsername()
    {
        $this->render('editUsername');
    }

    public function editPhone()
    {
        $this->render('editPhone');
    }

    public function userProfile()
    {
        $this->render('userProfile');
    }
}
