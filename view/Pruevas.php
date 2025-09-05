<?php
session_start();
$id_rol = $_SESSION['user_id_rol'] ?? null;
echo "contenido de la variabel". $id_rol ."tipo de dato". gettype($id_rol); 