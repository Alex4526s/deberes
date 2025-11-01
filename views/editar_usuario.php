<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario - Hornipan</title>
    <link rel="stylesheet" href="assets/css/styleEditarTecnico.css">
    <link rel="stylesheet" href="assets/css/estiloEditar_usuario.css">
</head>

<body>

<!-- 🔥 Barra superior -->
<div class="topbar">
    <div><img src="assets/hornipan.png" alt="Hornipan" style="height:35px;background:white;padding:5px;border-radius:5px;"></div>
    <div class="user-info">
        <?= htmlspecialchars($_SESSION['usuario']) ?>
        <a href="logout.php" style="color:white;text-decoration:none;margin-left:5px;font-weight:bold;">🚪 Salir</a>
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

<!-- 🔥 Contenido principal -->
<div class="content">
    <h2>Editar Usuario</h2>

    <div class="form-card">
        <form method="POST">
            <label>Nombre de Usuario:</label>
            <input type="text" name="usuario" value="<?= htmlspecialchars($usuario['usuario']) ?>" required>

            <label>Nueva Contraseña (opcional):</label>
            <input type="password" name="clave" placeholder="Dejar vacío si no deseas cambiarla">

            <button type="submit" class="btn-actualizar">Actualizar Usuario</button>
        </form>
    </div>
</div>

</body>
</html>
