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
            $usernameOrEmail = $_POST['username']; // Puede ser username o email
            $password = $_POST['password'];

            // Buscar usuario por username o email
            $user = $this->model->getUserByUsernameOrEmail($usernameOrEmail);

            if ($user && password_verify($password, $user['password'])) {
                // Iniciar sesión
                $_SESSION['user_id_usuario'] = $user['id_usuario'];
                $_SESSION['user_name'] = $user['username'];
                $_SESSION['user_id_rol'] = $user['id_rol'];  // Guardar el rol del usuario
                $_SESSION['user id_agricultor'] = $user['id_agricultor']; 

             //   if (isset($_SESSION['user_id_usuario'])) {
              //   echo "Sesión iniciada correctamente. Usuario ID: " . $_SESSION['user_id_usuario']."  ". $_SESSION['user_name']."  ". $_SESSION['user_id_rol'];
                //} else {
                 //echo "Error: la sesión no se inició.";
                //}
                header("Location: ../index.php");
                exit;
            } else {
                //Error de login
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