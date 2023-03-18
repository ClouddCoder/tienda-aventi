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

    public function addToShoppingCart(array $request)
    {
        $db = new DB();
        $pdo = $db->connect();

        $statement = $pdo->prepare("INSERT INTO shopping_cart (user_id, product_id) VALUES (:user_id, :product_id)");

        $statement->execute([
            'user_id' => $_SESSION['user_id'],
            'product_id' => $request['id']
        ]);

        $count = $statement->rowCount();

        // If the statement was executed successfully and the product exists.
        if ($statement) {
            if ($count > 0) {
                echo '<script>
                    alert("Producto agregado al carrito");
                    window.location.href = "/";
                    </script>';
            } else {
                echo '<script>
                    alert("El producto no existe");
                    window.location.href = "/";
                    </script>';
            }
        } else {
            echo '<script>
                alert("Error al agregar el producto al carrito");
                window.location.href = "/";
                </script>';
        }
    }

    public function removeFromShoppingCart(array $request)
    {
        $db = new DB();
        $pdo = $db->connect();

        $statement = $pdo->prepare("DELETE FROM shopping_cart WHERE user_id = :user_id AND product_id = :product_id");

        $statement->execute([
            'user_id' => $_SESSION['user_id'],
            'product_id' => $request['id']
        ]);

        $count = $statement->rowCount();

        // If the statement was executed successfully and the product exists.
        if ($statement) {
            if ($count > 0) {
                echo '<script>
                    alert("Producto eliminado del carrito");
                    window.location.href = "/shopping-cart";
                    </script>';
            } else {
                echo '<script>
                    alert("El producto no existe");
                    window.location.href = "/shopping-cart";
                    </script>';
            }
        } else {
            echo '<script>
                alert("Error al eliminar el producto del carrito");
                window.location.href = "/shopping-cart";
                </script>';
        }
    }

    public function buyProduct(array $request)
    {
        $db = new DB();
        $pdo = $db->connect();

        if ($request['quantity'] > $request['stock']) {
            echo '<script>
                alert("No hay suficiente stock");
                window.location.href = "/";
                </script>';
            return;
        }

        // Creates a new invoice.
        $stmtInvoice = $pdo->prepare("INSERT INTO invoice (user_id) VALUES (:user_id)");

        $stmtInvoice->execute([
            'user_id' => $_SESSION['user_id']
        ]);

        // Inserts the product purchased into the product_purchased table.
        $stmtProductPurchased = $pdo->prepare(
            "INSERT INTO product_purchased (invoice_id, product_id, quantity)
            VALUES (:invoice_id, :product_id, :quantity)"
        );

        $stmtProductPurchased->execute([
            'invoice_id' => $pdo->lastInsertId(),
            'product_id' => $request['id'],
            'quantity' => $request['quantity']
        ]);

        // Updates the product stock.
        $stmtUpdateProductStock = $pdo->prepare(
            "UPDATE product SET stock = :stock WHERE id = :id"
        );

        $stmtUpdateProductStock->execute([
            'stock' => $request['stock'] - $request['quantity'],
            'id' => $request['id']
        ]);

        $count = $stmtUpdateProductStock->rowCount();

        // If the statement was executed successfully and the product exists.
        if ($stmtProductPurchased) {
            if ($count > 0) {
                echo '<script>
                    alert("Producto comprado");
                    window.location.href = "/";
                    </script>';
            } else {
                echo '<script>
                    alert("El producto no existe");
                    window.location.href = "/";
                    </script>';
            }
        } else {
            echo '<script>
                alert("Error al comprar el producto");
                window.location.href = "/";
                </script>';
        }
    }
}
