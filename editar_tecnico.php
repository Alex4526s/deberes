<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: historial_tecnicos.php');
    exit();
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM tecnicos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$tecnico = $resultado->fetch_assoc();
$stmt->close();

if (!$tecnico) {
    echo "❌ Técnico no encontrado.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $celular = $_POST['celular'];

    $stmt = $conn->prepare("
        UPDATE tecnicos
        SET nombre = ?, correo = ?, celular = ?
        WHERE id = ?
    ");
    $stmt->bind_param("sssi", $nombre, $correo, $celular, $id);

    if ($stmt->execute()) {
        header('Location: historial_tecnicos.php');
        exit();
    } else {
        echo "❌ Error al actualizar técnico.";
    }
    $stmt->close();
}

include 'views/editar_tecnico_form.php';
?>
