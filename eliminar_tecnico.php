<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $tecnico_id = intval($_GET['id']); 
    $stmt = $conn->prepare("DELETE FROM tecnicos WHERE id = ?");
    $stmt->bind_param("i", $tecnico_id);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "✅ Técnico eliminado correctamente.";
        $_SESSION['mensaje_tipo'] = "success"; 
    } else {
        $_SESSION['mensaje'] = "❌ Error al eliminar el técnico.";
        $_SESSION['mensaje_tipo'] = "error";
    }

    $stmt->close();
} else {
    $_SESSION['mensaje'] = "❌ ID no válido.";
    $_SESSION['mensaje_tipo'] = "error";
}

header('Location: historial_tecnicos.php');
exit();
?>
