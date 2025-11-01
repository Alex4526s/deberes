<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Registrar Usuario - Hornipan</title>
  <link rel="stylesheet" href="assets/css/styleEditarTecnico.css"> <!-- ✅ Usando el mismo CSS que Registrar Técnico -->
  <script src="assets/js/agregar_usuario.js" defer></script> <!-- Opcional: para validar contraseñas -->
  <link rel="stylesheet" href="assets/css/agregar_usuario.css"> <!-- ✅ Usando el mismo CSS que Registrar Técnico -->
  
</head>

<body>

  <div class="barra-superior">
    <div class="barra-izquierda">
      <img src="assets/hornipan.png" alt="Hornipan">
    </div>
    <div class="barra-derecha">
      <span class="usuario"><?= htmlspecialchars($_SESSION['usuario']) ?></span>
      <a href="logout.php" class="enlace-salir">🚪 Salir</a>
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
    <h2>Registrar Usuario</h2>

    <!-- 🔥 Mostrar mensaje si existe -->
    <?php if (!empty($mensaje)): ?>
      <div class="mensaje 
        <?php 
          if (strpos($mensaje, '✅') !== false) echo 'mensaje-exito';
          elseif (strpos($mensaje, '⚠') !== false) echo 'mensaje-advertencia';
          else echo 'mensaje-error';
        ?>">
        <?= $mensaje ?>
      </div>
    <?php endif; ?>

    <div class="form-card">
      <form method="POST" id="formAgregarUsuario">
        <label>Nombre de Usuario:</label>
        <input type="text" name="usuario" required>

        <label>Contraseña:</label>
        <input type="password" name="clave" required>

        <label>Confirmar Contraseña:</label>
        <input type="password" name="confirmar_clave" required>

        <button type="submit">Registrar Usuario</button>
      </form>
    </div>
  </div>

</body>

</html>
