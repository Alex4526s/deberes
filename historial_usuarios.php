<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "hornipan");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
$resultado = $conexion->query("SELECT id, usuario FROM usuarios ORDER BY id ASC");

$usuarios = [];
if ($resultado && $resultado->num_rows > 0) {
    $usuarios = $resultado->fetch_all(MYSQLI_ASSOC);
}

include 'views/Historial_Usuarios.php';
?>

