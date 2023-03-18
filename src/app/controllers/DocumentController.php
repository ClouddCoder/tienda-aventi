<?php

namespace Tienda\App\Controllers;

use Tienda\App\Libs\Controller;
use Tienda\App\Models\DB;

class DocumentController extends Controller
{

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->route('/login');
            exit;
        }

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
        if (!isset($_SESSION['user_id'])) {
            $this->route('/login');
            exit;
        }

        $this->render('editEmail');
    }

    public function editPassword()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->route('/login');
            exit;
        }

        $this->render('editPassword');
    }

    public function editUsername()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->route('/login');
            exit;
        }

        $this->render('editUsername');
    }

    public function editPhone()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->route('/login');
            exit;
        }

        $this->render('editPhone');
    }

    public function userProfile()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->route('/login');
            exit;
        }

        $db = new DB();
        $pdo = $db->connect();

        $statement = $pdo->prepare("SELECT * FROM user WHERE id = :id");
        $statement->execute(['id' => $_SESSION['user_id']]);

        $user = $statement->fetchAll();

        $data = ['user' => $user[0]];

        $this->render('userProfile', $data);
    }
}
