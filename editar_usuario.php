<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "hornipan");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID de usuario no especificado.");
}

$resultado = $conexion->query("SELECT * FROM usuarios WHERE id = $id");

if (!$resultado || $resultado->num_rows == 0) {
    die("Usuario no encontrado.");
}

$usuario = $resultado->fetch_assoc();
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoUsuario = $conexion->real_escape_string($_POST['usuario']);
    $nuevaClave = $_POST['clave'];

    if (!empty($nuevaClave)) {
        $passwordHash = password_hash($nuevaClave, PASSWORD_DEFAULT);
        $conexion->query("UPDATE usuarios SET usuario='$nuevoUsuario', password='$passwordHash', primera_vez=1 WHERE id=$id");
    } else {
        $conexion->query("UPDATE usuarios SET usuario='$nuevoUsuario' WHERE id=$id");
    }

    header("Location: historial_usuarios.php");
    exit();
}

include 'views/editar_usuario.php';
?>

