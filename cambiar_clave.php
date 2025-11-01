<?php
session_start();
include('db.php');

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nueva = $_POST['nueva_clave'];
    $confirmar = $_POST['confirmar_clave'];

    if ($nueva !== $confirmar) {
        $mensaje = "❌ Las contraseñas no coinciden.";
    } elseif (strlen($nueva) < 8) {
        $mensaje = "❌ La contraseña debe tener al menos 8 caracteres.";
    } else {
        $clave_hash = password_hash($nueva, PASSWORD_BCRYPT);
        $usuario = $_SESSION['usuario'];

        $stmt = $conn->prepare("UPDATE usuarios SET password = ?, primera_vez = 0 WHERE usuario = ?");
        $stmt->bind_param("ss", $clave_hash, $usuario);

        if ($stmt->execute()) {
            session_destroy();
            header("Location: login.php?registro=clave_ok");
            exit();
        } else {
            $mensaje = "❌ Error al actualizar la contraseña. Inténtalo nuevamente.";
        }
    }
}

include 'views/cambiar_clave_form.php';
?>
