<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

$tecnicos = $conn->query("SELECT * FROM tecnicos");

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tecnico_id = $_POST['tecnico_id'];
    $tipo = $_POST['tipo'];
    $fecha = $_POST['fecha'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];
    $archivoNombre = null;

    $stmtTecnico = $conn->prepare("SELECT * FROM tecnicos WHERE id = ?");
    $stmtTecnico->bind_param("i", $tecnico_id);
    $stmtTecnico->execute();
    $resultado = $stmtTecnico->get_result();
    $tecnico = $resultado->fetch_assoc();
    $stmtTecnico->close();

    if (!$tecnico) {
        $mensaje = "❌ Error: Técnico no encontrado.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM ordenes WHERE tecnico_email = ? AND fecha = ? 
            AND (
                (hora_inicio <= ? AND hora_fin > ?) OR
                (hora_inicio < ? AND hora_fin >= ?) OR
                (hora_inicio >= ? AND hora_fin <= ?)
            )");
        $stmt->bind_param("ssssssss", $tecnico['correo'], $fecha, $hora_inicio, $hora_inicio, $hora_fin, $hora_fin, $hora_inicio, $hora_fin);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $mensaje = "⛔ El técnico ya tiene una orden en ese horario.";
        } else {
            if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
                $archivoNombre = time() . "_" . basename($_FILES["archivo"]["name"]);
                move_uploaded_file($_FILES["archivo"]["tmp_name"], "uploads/" . $archivoNombre);
            }
            $stmtInsert = $conn->prepare("INSERT INTO ordenes (tecnico_email, tecnico_nombre, tipo, fecha, hora_inicio, hora_fin, archivo) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmtInsert->bind_param("sssssss", $tecnico['correo'], $tecnico['nombre'], $tipo, $fecha, $hora_inicio, $hora_fin, $archivoNombre);

            if ($stmtInsert->execute()) {
                $mensaje = "✅ Orden registrada exitosamente.";
            } else {
                $mensaje = "❌ Error al registrar la orden.";
            }
            $stmtInsert->close();
        }
        $stmt->close();
    }
}

include 'views/registrar_orden_form.php';
?>
