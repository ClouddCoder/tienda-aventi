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

    public function deleteProduct(array $request)
    {
        $db = new DB();
        $pdo = $db->connect();

        $statement = $pdo->prepare("DELETE FROM product WHERE id = :id");

        $statement->execute([
            'id' => $request['id']
        ]);

        $count = $statement->rowCount();

        // If the statement was executed successfully and the product exists.
        if ($statement) {
            if ($count > 0) {
                echo '<script>
                    alert("Producto eliminado");
                    window.location.href = "/all-products";
                    </script>';
            } else {
                echo '<script>
                    alert("El producto no existe");
                    window.location.href = "/all-products";
                    </script>';
            }
        } else {
            echo '<script>
                alert("Error al eliminar el producto");
                window.location.href = "/all-products";
                </script>';
        }
    }

    public function setProductStatus(array $request)
    {
        $db = new DB();
        $pdo = $db->connect();

        $statement = $pdo->prepare("UPDATE product SET status = :status WHERE id = :id");

        $statement->execute([
            'status' => $request['status'],
            'id' => $request['id']
        ]);

        $count = $statement->rowCount();

        // If the statement was executed successfully and the product exists.
        if ($statement) {
            if ($count > 0) {
                echo '<script>
                    alert("Estado del producto actualizado");
                    window.location.href = "/all-products";
                    </script>';
            } else {
                echo '<script>
                    alert("El producto no existe");
                    window.location.href = "/all-products";
                    </script>';
            }
        } else {
            echo '<script>
                alert("Error al actualizar el estado del producto");
                window.location.href = "/all-products";
                </script>';
        }
    }
}