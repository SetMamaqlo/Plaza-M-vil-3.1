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
            $usernameOrEmail = trim($_POST['username']); // Username o email
            $password = trim($_POST['password']);

            // Buscar usuario
            $user = $this->model->getUserByUsernameOrEmail($usernameOrEmail);

            

            if ($user && password_verify($password, $user['password'])) {
                // Guardar datos básicos en sesión
                $_SESSION['user_id_usuario'] = (int)$user['id_usuario'];
                $_SESSION['user_name'] = $user['username'];
                $_SESSION['user_id_rol'] = (int)$user['id_rol'];

                // Guardar id_agricultor si existe
                if (!empty($user['id_agricultor'])) {
                    $_SESSION['user_id_agricultor'] = (int)$user['id_agricultor'];
                }

                // Redirección según el rol
                switch ($_SESSION['user_id_rol']) {
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
        // Limpiar sesión de forma segura
        session_unset();
        session_destroy();
        header("Location: ../view/login.php");
        exit;
    }
}

// Ejecutar acción
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    $controller = new LoginController($pdo);
    $controller->login();
} elseif (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $controller = new LoginController($pdo);
    $controller->logout();
}