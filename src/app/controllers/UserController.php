<?php

namespace Tienda\App\Controllers;

use Tienda\App\Libs\Controller;

class UserController extends Controller
{

    public function login()
    {
        if ($_POST['email'] == 'admin@admin.com' && $_POST['password'] == 'admin') {
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
}
