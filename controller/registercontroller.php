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
            $role = $_POST['role'];

            // Validar que el rol sea válido
            if (!in_array($role, ['vendedor', 'comprador', 'administrador'])) {
                header("Location: ../view/register.php?error=invalid_role");
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
                    $role
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