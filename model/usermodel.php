<?php
class UserModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getUserByUsernameOrEmail($usernameOrEmail) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE username = ? OR email = ?");
        $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function addUser($nombre_completo, $tipo_documento, $numero_documento, $telefono, $email, $fecha_nacimiento, $username, $password,  $id_rol)
    {
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nombre_completo, tipo_documento, numero_documento, telefono, email, fecha_nacimiento, username, password, id_rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$nombre_completo, $tipo_documento, $numero_documento, $telefono, $email, $fecha_nacimiento, $username, $password , $id_rol]);
    }

}
?>