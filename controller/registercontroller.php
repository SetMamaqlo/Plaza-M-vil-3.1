<?php
require_once '../model/usermodel.php';
require_once '../config/conexion.php';

class RegisterController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new UserModel($pdo);
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre_completo = $_POST['nombre_completo'];
            $tipo_documento = $_POST['tipo_documento'];
            $numero_documento = $_POST['numero_documento'];
            $telefono = $_POST['telefono'];
            $email = $_POST['email'];
            $fecha_nacimiento = $_POST['fecha_nacimiento'];
            $username = $_POST['username'];
            $password = $_POST['password']; // Contraseña sin encriptar
            $rolSeleccionado = $_POST['id_rol']; // "comprador"

switch ($rolSeleccionado) {
    case "admin":
        $id_rol = 1;
        break;
    case "comprador":
        $id_rol = 2;
        break;
    case "vendedor":
        $id_rol = 3;
        break;
    default:
        $id_rol = 2; // Por defecto "comprador"
}
            

            // Validar que el rol sea válido
            if (!in_array($id_rol, ['1', '2', '3'])) {
                header("Location: ../view/register.php?error=invalid_id_rol");
                exit;
            }

            // Encriptar la contraseña
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Registrar el usuario en la base de datos
            if (
                $this->model->addUser(
                    $nombre_completo,
                    $tipo_documento,
                    $numero_documento,
                    $telefono,
                    $email,
                    $fecha_nacimiento,
                    $username,
                    $hashedPassword,                  
                    id_rol: $id_rol
                    
                )
            ) {
                header("Location: ../view/register.php?success=1");
                exit;
            } else {
                header("Location: ../view/register.php?error=database_error");
                exit;
            }
        }
    }
}

// Instancia del controlador y ejecución del método
$controller = new RegisterController($pdo);
$controller->register();