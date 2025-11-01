<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $celular = trim($_POST['celular']);

    if (!empty($nombre) && !empty($correo) && !empty($celular)) {
        $stmt = $conn->prepare("INSERT INTO tecnicos (nombre, correo, celular) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $correo, $celular);

        if ($stmt->execute()) {
            $mensaje = "✅ Técnico registrado exitosamente.";
        } else {
            $mensaje = "❌ Error al registrar el técnico.";
        }

        $stmt->close();
    } else {
        $mensaje = "❗ Todos los campos son obligatorios.";
    }
}

include 'views/registrar_tecnico_view.php';
?>
