<?php

namespace Tienda\App\Controllers;

use Tienda\App\Libs\Controller;
use Tienda\App\Models\DB;

class DocumentController extends Controller
{

    public function getEnabledProducts()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->route('/login');
            exit;
        }

        $db = new DB();
        $pdo = $db->connect();

        // Gets all products.
        $statement = $pdo->prepare("SELECT * FROM product WHERE status = 'enabled'");
        $statement->execute();

        $products = $statement->fetchAll();

        $data = ['products' => $products ?? []];

        $this->render('index', $data);
    }

    public function shoppingCart()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->route('/login');
            exit;
        }

        $db = new DB();
        $pdo = $db->connect();

        // Gets all products.
        $statement = $pdo->prepare(
            "SELECT * FROM product
            INNER JOIN shopping_cart ON product.id = shopping_cart.product_id
            WHERE shopping_cart.user_id = :user_id"
        );

        $statement->execute(['user_id' => $_SESSION['user_id']]);

        $products = $statement->fetchAll();

        $data = ['products' => $products ?? []];

        $this->render('shoppingCart', $data);
    }

    public function adminPanel()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->route('/login');
            exit;
        }

        $this->render('adminPanel');
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

    public function allUsers()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->route('/login');
            exit;
        }

        if ($_SESSION['user_id'] == 3) {
            $this->route('/');
            exit;
        }

        $db = new DB();
        $pdo = $db->connect();

        // Gets all usernames with role of client.
        $statement = $pdo->prepare("SELECT user.id, username FROM user WHERE role_id = 3");
        $statement->execute();

        $users = $statement->fetchAll();

        $data = ['users' => $users ?? []];

        $this->render('allUsers', $data);
    }

    public function allSupervisors()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->route('/login');
            exit;
        }

        if ($_SESSION['user_id'] == 3) {
            $this->route('/');
            exit;
        }

        $db = new DB();
        $pdo = $db->connect();

        // Gets all usernames with role of supervisor.
        $statement = $pdo->prepare("SELECT user.id, username FROM user WHERE role_id = 2");
        $statement->execute();

        $supervisors = $statement->fetchAll();

        $data = ['supervisors' => $supervisors ?? []];

        $this->render('allSupervisors', $data);
    }

    public function allProducts()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->route('/login');
            exit;
        }

        if ($_SESSION['user_id'] == 3) {
            $this->route('/');
            exit;
        }

        $db = new DB();
        $pdo = $db->connect();

        // Gets all products.
        $statement = $pdo->prepare("SELECT * FROM product");
        $statement->execute();

        $products = $statement->fetchAll();

        $data = ['products' => $products ?? []];

        $this->render('allProducts', $data);
    }

    public function invoice()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->route('/login');
            exit;
        }

        $db = new DB();
        $pdo = $db->connect();

        $statement = $pdo->prepare(
            "SELECT user.username, product.name, product.price, invoice.id, product_purchased.quantity
            FROM product
            INNER JOIN product_purchased ON product.id = product_purchased.product_id
            INNER JOIN invoice ON product_purchased.invoice_id = invoice.id
            INNER JOIN user ON invoice.user_id = user.id
            WHERE user.id = :user_id
            ORDER BY invoice.id DESC
            LIMIT 1"
        );

        $statement->execute(['user_id' => $_SESSION['user_id']]);

        $products = $statement->fetchAll();

        $data = ['products' => $products ?? []];

        $this->render('invoice', $data);
    }
}
