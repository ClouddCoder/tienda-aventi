<?php

namespace Tienda\App\Controllers;

use Tienda\App\Libs\Controller;
use Tienda\App\Models\DB;

class ProductController extends Controller
{

    public function createProduct(array $request)
    {
        $db = new DB();
        $pdo = $db->connect();

        $statement = $pdo->prepare("INSERT INTO product (name, price, stock, status) VALUES (:name, :price, :stock, :status)");

        $statement->execute([
            'name' => $request['name'],
            'price' => $request['price'],
            'stock' => $request['stock'],
            'status' => $request['status']
        ]);

        $lastInsert = $pdo->prepare("SELECT LAST_INSERT_ID();");
        $lastInsert->execute();

        $productId = $lastInsert->fetchAll();

        if ($productId) {
            $this->route('/all-products');
        } else {
            echo '<script>
                alert("Error al crear producto");
                window.location.href = "/all-products";
                </script>';
        }
    }
}
