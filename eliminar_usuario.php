<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "hornipan");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id = $_GET['id'] ?? null;

if ($id) {
    $conexion->query("DELETE FROM usuarios WHERE id = $id");
}

// Después de eliminar, regresa al historial
header("Location: historial_usuarios.php");
exit();
?>
