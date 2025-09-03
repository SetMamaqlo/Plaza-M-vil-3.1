<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../model/productmodel.php';
require_once '../config/conexion.php';

class ProductController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new ProductModel($pdo);
    }

    public function addProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $categoria = $_POST['categoria'];
            $descripcion = $_POST['descripcion'];
            $precio = $_POST['precio'];
            $id_usuario = $_SESSION['user_id']; // ID del usuario que publica el producto

            // Procesar la imagen
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $imagen = $_FILES['imagen']['name'];
                $ruta_destino = '../img/' . $imagen;

                // Mover la imagen a la carpeta destino
                if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
                    echo "<script>
                            alert('Error al subir la imagen.');
                            window.location.href = '../view/add_product_views.php';
                          </script>";
                    exit;
                }
            } else {
                echo "<script>
                        alert('Por favor, sube una imagen válida.');
                        window.location.href = '../view/add_product_views.php';
                      </script>";
                exit;
            }

            // Insertar el producto en la base de datos
            if ($this->model->addProduct($nombre, $descripcion, $precio, $imagen, $id_usuario, $categoria)) {
                echo "<script>
            alert('¡Producto añadido con éxito!');
            window.location.href = '../view/mis_productos.php?add=ok';
          </script>";
            } else {
                echo "<script>
            alert('Error al añadir el producto.');
            window.location.href = '../view/add_product_views.php';
          </script>";
            }
        }
    }
}

// Instancia del controlador y ejecución del método
$controller = new ProductController($pdo);
$controller->addProduct();
?>