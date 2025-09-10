<?php
session_start();
require_once '../model/usermodel.php';
require_once '../config/conexion.php';

class LoginController {
    private $model;

    public function __construct($pdo) {
        $this->model = new UserModel($pdo);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usernameOrEmail = trim($_POST['username']); // Puede ser username o email
            $password = trim($_POST['password']);

            // Buscar usuario por username o email (con id_agricultor incluido)
            $user = $this->model->getUserByUsernameOrEmail($usernameOrEmail);

            if ($user && password_verify($password, $user['password'])) {
                // Iniciar sesión
                $_SESSION['user_id_usuario'] = $user['id_usuario'];
                $_SESSION['user_name'] = $user['username'];
                $_SESSION['user_id_rol'] = $user['id_rol'];
                

                // 🔥 Guardamos id_agricultor si existe
                if (!empty($user['id_agricultor'])) {
                    $_SESSION['user_id_agricultor'] = $user['id_agricultor'];
                }

                // 🔥 Redirigir según el rol
                switch ($user['id_rol']) {
                    case 1: // Admin
                        header("Location: ../index.php");
                        break;
                    case 2: // Vendedor
                        header("Location: ../index.php");
                        break;
                    case 3: // Agricultor
                        header("Location: ../index.php");
                        break;
                    default:
                        header("Location: ../index.php");
                        break;
                }
                exit;
            } else {
                // Error de login
                header("Location: ../view/login.php?error=1");
                exit;
            }
        }
    }

    public function logout() {
        session_destroy();
        header("Location: ../view/login.php");
        exit;
    }
}

// Instancia del controlador y ejecución del método
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    $controller = new LoginController($pdo);
    $controller->login();
} elseif (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $controller = new LoginController($pdo);
    $controller->logout();
}
?>