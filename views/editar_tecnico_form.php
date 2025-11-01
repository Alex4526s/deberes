<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Técnico - Hornipan</title>
    <link rel="stylesheet" href="assets/css/styleEditarTecnico.css">
    <script src="assets/js/editar_tecnico.js" defer></script>
</head>

<body>

    <div class="topbar">
        <div><img src="assets/hornipan.png" alt="Hornipan"
                style="height: 35px; background:white; padding:5px; border-radius:5px;"></div>
        <div class="user-info">
            <?= htmlspecialchars($_SESSION['usuario']) ?>
            <a href="logout.php" style="color:white; text-decoration:none; margin-left:5px; font-weight:bold;">🚪
                Salir</a>
        </div>
    </div>

    <div class="sidebar">
    <a href="index.php">🏠 Inicio</a>
    <strong style="padding-left:20px;">★ Administración</strong>
    <a href="registrar_tecnico.php">➕ Ingresar Técnico</a>
    <a href="registrar_orden.php">➕ Ingresar Orden</a>
    <a href="agregar_usuario.php">➕ Registrar Usuario</a>
    <strong style="padding-left:20px;">Historiales</strong>
    <a href="ficha_tecnica.php" style="padding-left: 40px;">📁 Historial de órdenes</a>
    <a href="historial_tecnicos.php" style="padding-left: 40px;">📁 Historial de técnicos</a>
    <a href="historial_usuarios.php" style="padding-left: 40px;">📁 Historial de usuarios</a>
  </div>
    <div class="content">
        <h2>Editar Técnico</h2>

        <form method="POST" id="formEditarTecnico">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($tecnico['nombre']) ?>" required>

            <label>Correo:</label>
            <input type="email" name="correo" value="<?= htmlspecialchars($tecnico['correo']) ?>" required>

            <label>Celular:</label>
            <input type="text" name="celular" value="<?= htmlspecialchars($tecnico['celular']) ?>" required>

            <button type="submit">Actualizar Técnico</button>
        </form>
    </div>

</body>

</html>