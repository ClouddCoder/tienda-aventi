<?php

namespace Tienda\App\Controllers;

use Tienda\App\Libs\Controller;
use Models\DB;

class UserController extends Controller
{

    public function login(array $request)
    {
        if ($request['email'] == 'admin@admin.com' && $request['password'] == 'admin') {
            $_SESSION['user_id'] = 1;

            $this->route('/user-profile');
        } else {
            echo '<script>
                alert("Usuario o contraseña incorrectos");
                window.location.href = "/login";
                </script>';
        }
    }

    public function register()
    {
        $this->render('login');
    }

    public function editEmail(array $request)
    {
        $db = new DB;
        $pdo = $db->connect();

        $email = $request['email'];
        $user_id = $_SESSION['user_id'];

        $statement = $pdo->prepare('UPDATE users SET email = :email WHERE id = :user_id');
        $statement->execute(['email' => $email, 'user_id' => $user_id]);

        $this->render('user-profile');
    }
}
