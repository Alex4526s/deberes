<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $orden_id = intval($_GET['id']); 

    $stmt = $conn->prepare("DELETE FROM ordenes WHERE id = ?");
    $stmt->bind_param("i", $orden_id);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "✅ Orden eliminada correctamente.";
        $_SESSION['mensaje_tipo'] = "success";
    } else {
        $_SESSION['mensaje'] = "❌ Error al eliminar la orden.";
        $_SESSION['mensaje_tipo'] = "error";
    }

    $stmt->close();
} else {
    $_SESSION['mensaje'] = "❌ ID inválido para eliminar.";
    $_SESSION['mensaje_tipo'] = "error";
}

header('Location: ficha_tecnica.php');
exit();
?>
