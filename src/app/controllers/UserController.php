<?php

namespace Tienda\App\Controllers;

use Tienda\App\Libs\Controller;
use Tienda\App\Models\DB;

class UserController extends Controller
{

    public function login(array $request)
    {
        $db = new DB();
        $pdo = $db->connect();

        $statement = $pdo->prepare("SELECT user.id FROM user WHERE email = :email AND password = :password");
        $statement->execute(['email' => $request['email'], 'password' => $request['password']]);

        $user = $statement->fetchAll();

        if ($user) {
            $_SESSION['user_id'] = $user[0]['id'];

            $this->route('/user-profile');
        } else {
            echo '<script>
                alert("Usuario o contraseña incorrectos");
                window.location.href = "/login";
                </script>';
        }
    }

    public function register(array $request)
    {
        $db = new DB();
        $pdo = $db->connect();

        $statement = $pdo->prepare("INSERT INTO user (role_id, username, phone, email, password) VALUES (:role_id, :username, :phone, :email, :password)");

        $statement->execute([
            'role_id' => 3,
            'username' => $request['username'],
            'phone' => $request['phone'],
            'email' => $request['email'],
            'password' => $request['password']
        ]);

        // Gets last inserted id.
        $lastInsert = $pdo->prepare("SELECT LAST_INSERT_ID();");
        $lastInsert->execute();

        $userId = $lastInsert->fetchAll();

        if ($userId) {
            $_SESSION['user_id'] = $userId[0]['LAST_INSERT_ID()'];

            $this->route('/user-profile');
        } else {
            echo '<script>
                alert("Error al registrar usuario");
                window.location.href = "/register";
                </script>';
        }
    }

    public function forgotPassword(array $request)
    {
        $db = new DB();
        $pdo = $db->connect();

        $email = $request['email'];
        $newPassword = $request['new-password'];

        $statement = $pdo->prepare('UPDATE user SET password = :new_password WHERE email = :email');
        $statement->execute(['email' => $email, 'new_password' => $newPassword]);

        $count = $statement->rowCount();

        // If the statement was executed successfully and the email exists.
        if ($statement) {
            if ($count > 0) {
                echo '<script>
                    alert("Contraseña actualizada");
                    window.location.href = "/login";
                    </script>';
            } else {
                echo '<script>
                    alert("El correo no existe");
                    window.location.href = "/forgot-password";
                    </script>';
            }
        } else {
            echo '<script>
                alert("Error al actualizar contraseña");
                window.location.href = "/forgot-password";
                </script>';
        }
    }

    public function editEmail(array $request)
    {
        $db = new DB();
        $pdo = $db->connect();

        $email = $request['email'];
        $user_id = $_SESSION['user_id'];

        $statement = $pdo->prepare('UPDATE user SET email = :email WHERE user.id = :user_id');
        $statement->execute(['email' => $email, 'user_id' => $user_id]);

        $count = $statement->rowCount();

        // If the statement was executed successfully and the email is different from the current one.
        if ($statement) {
            if ($count > 0) {
                echo '<script>
                    alert("Email actualizado");
                    window.location.href = "/user-profile";
                    </script>';
            } else {
                echo '<script>
                    alert("El email tiene que ser diferente al actual");
                    window.location.href = "/edit-email";
                    </script>';
            }
        } else {
            echo '<script>
                alert("Error al actualizar email");
                window.location.href = "/edit-email";
                </script>';
        }

        $this->render('user-profile');
    }
}
