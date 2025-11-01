<?php
session_start();
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('db.php');

    $usuario = $_POST['usuario'];
    $clave1 = $_POST['clave'];
    $clave2 = $_POST['confirmar_clave'];

    if ($clave1 !== $clave2) {
        $mensaje = "⚠ Las contraseñas no coinciden.";
    } else {
        $clave_hash = password_hash($clave1, PASSWORD_BCRYPT);

        $verificar = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ?");
        $verificar->bind_param("s", $usuario);
        $verificar->execute();
        $verificar->store_result();

        if ($verificar->num_rows > 0) {
            $mensaje = "⚠ El usuario ya existe.";
        } else {
            $stmt = $conn->prepare("INSERT INTO usuarios (usuario, password, primera_vez) VALUES (?, ?, 1)");
            $stmt->bind_param("ss", $usuario, $clave_hash);

            if ($stmt->execute()) {
                $mensaje = "✅ Usuario creado correctamente. Ya puedes iniciar sesión.";
            } else {
                $mensaje = "❌ Error al registrar el usuario.";
            }
        }
    }
}

include 'views/agregar_usuario_form.php';
?>
