<?php
session_start();
require 'db.php'; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->bind_param('s', $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuarioData = $resultado->fetch_assoc();

        if (password_verify($clave, $usuarioData['password'])) {
            $_SESSION['usuario_id'] = $usuarioData['id'];
            $_SESSION['usuario'] = $usuarioData['usuario'];

            if ($usuarioData['primera_vez'] == 1) {
                header('Location: cambiar_clave.php');
            } else {
                header('Location: index.php');
            }
            exit();
        } else {
            header('Location: login.php?error=clave');
            exit();
        }
    } else {
        header('Location: login.php?error=usuario');
        exit();
    }
}

include 'views/login_form.php';
?>
