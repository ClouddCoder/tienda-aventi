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

            // If the user is an admin or a supervisor.
            if ($_SESSION['user_id'] == 1 || $_SESSION['user_id'] == 2) {
                $this->route('/admin-panel');
                exit;
            }

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
        $url = "";

        $statement = $pdo->prepare("INSERT INTO user (role_id, username, phone, email, password) VALUES (:role_id, :username, :phone, :email, :password)");

        switch ($request['id']) {
            case 2:
                $url = "/all-supervisors";
                break;
            default:
                $url = "/register";
                break;
        }

        try {
            $statement->execute([
                'role_id' => $request['id'] ?? 3,
                'username' => $request['username'],
                'phone' => $request['phone'],
                'email' => $request['email'],
                'password' => $request['password']
            ]);
        } catch (\PDOException $e) {
            echo '<script>
            alert("Error al registrar usuario: ' . $e->getMessage() . '");
            window.location.href = "' . $url . '";
            </script>';
            exit;
        }


        // Gets last inserted id.
        $lastInsert = $pdo->prepare("SELECT LAST_INSERT_ID();");
        $lastInsert->execute();

        $userId = $lastInsert->fetchAll();

        if ($userId) {
            // If the registered user is a supervisor.
            if ($request['id'] == 2) {
                $this->route('/all-supervisors');
                exit;
            }

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
    }

    public function editPassword(array $request)
    {
        $db = new DB();
        $pdo = $db->connect();

        $password = $request['password'];
        $user_id = $_SESSION['user_id'];

        $statement = $pdo->prepare('UPDATE user SET password = :password WHERE user.id = :user_id');
        $statement->execute(['password' => $password, 'user_id' => $user_id]);

        $count = $statement->rowCount();

        // If the statement was executed successfully and the password is different from the current one.
        if ($statement) {
            if ($count > 0) {
                echo '<script>
                    alert("Contraseña actualizada");
                    window.location.href = "/user-profile";
                    </script>';
            } else {
                echo '<script>
                    alert("La contraseña tiene que ser diferente a la actual");
                    window.location.href = "/edit-password";
                    </script>';
            }
        } else {
            echo '<script>
                alert("Error al actualizar contraseña");
                window.location.href = "/edit-password";
                </script>';
        }
    }

    public function editPhone(array $request)
    {
        $db = new DB();
        $pdo = $db->connect();

        $phone = $request['phone'];
        $user_id = $_SESSION['user_id'];

        $statement = $pdo->prepare('UPDATE user SET phone = :phone WHERE user.id = :user_id');
        $statement->execute(['phone' => $phone, 'user_id' => $user_id]);

        $count = $statement->rowCount();

        // If the statement was executed successfully and the phone is different from the current one.
        if ($statement) {
            if ($count > 0) {
                echo '<script>
                    alert("Teléfono actualizado");
                    window.location.href = "/user-profile";
                    </script>';
            } else {
                echo '<script>
                    alert("El teléfono tiene que ser diferente al actual");
                    window.location.href = "/edit-phone";
                    </script>';
            }
        } else {
            echo '<script>
                alert("Error al actualizar teléfono");
                window.location.href = "/edit-phone";
                </script>';
        }
    }

    public function editUsername(array $request)
    {
        $db = new DB();
        $pdo = $db->connect();

        $username = $request['username'];
        $user_id = $_SESSION['user_id'];

        $statement = $pdo->prepare('UPDATE user SET username = :username WHERE user.id = :user_id');
        $statement->execute(['username' => $username, 'user_id' => $user_id]);

        $count = $statement->rowCount();

        // If the statement was executed successfully and the username is different from the current one.
        if ($statement) {
            if ($count > 0) {
                echo '<script>
                    alert("Nombre de usuario actualizado");
                    window.location.href = "/user-profile";
                    </script>';
            } else {
                echo '<script>
                    alert("El nombre de usuario tiene que ser diferente al actual");
                    window.location.href = "/edit-username";
                    </script>';
            }
        } else {
            echo '<script>
                alert("Error al actualizar nombre de usuario");
                window.location.href = "/edit-username";
                </script>';
        }
    }

    public function deleteUser(array $request)
    {
        $db = new DB();
        $pdo = $db->connect();
        $url = "";

        $user_id = $request['id'];
        $role_id = $request['role-id'];

        $statement = $pdo->prepare('DELETE FROM user WHERE user.id = :user_id');
        $statement->execute(['user_id' => $user_id]);

        $count = $statement->rowCount();

        switch ($role_id) {
            case 2:
                $url = "/all-supervisors";
                break;
            default:
                $url = "/all-users";
        }

        // If the statement was executed successfully and the user exists.
        if ($statement) {
            if ($count > 0) {
                echo '<script>
                    alert("Usuario eliminado");
                    window.location.href = "' . $url . '";
                    </script>';
            } else {
                echo '<script>
                    alert("El usuario no existe");
                    window.location.href = "' . $url . '";
                    </script>';
            }
        } else {
            echo '<script>
                alert("Error al eliminar usuario");
                window.location.href = "' . $url . '";
                </script>';
        }
    }
}
