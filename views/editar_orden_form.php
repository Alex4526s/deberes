<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="assets/css/styleEditarOrden.css">
  <title>Editar Orden</title>
  <script src="assets/js/editar_orden.js" defer></script>
</head>

<body>

  <div class="topbar">
    <div><img src="assets/hornipan.png" alt="Hornipan"
        style="height:35px; background:white; padding:5px; border-radius:5px;"></div>
    <div class="user-info">
      <?= htmlspecialchars($_SESSION['usuario']) ?>
      <a href="logout.php" style="color:white; text-decoration:none; margin-left:5px; font-weight:bold;">🚪 Salir</a>
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
    <h2>Editar Orden</h2>

    <?php if (!empty($mensajeFinal)): ?>
      <div class="mensaje"><?= htmlspecialchars($mensajeFinal) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <label>Código de Pedido:</label>
      <input type="text" name="codigo_pedido" value="<?= htmlspecialchars($pedido) ?>" required>

      <label>Seleccione Técnico:</label>
      <select name="tecnico_id">
        <option value="">-- Sin técnico asignado --</option>
        <?php foreach ($tecnicos as $tecnico): ?>
          <option value="<?= $tecnico['id'] ?>" <?= ($tecnico['id'] == $tecnico_id) ? 'selected' : '' ?>>
            <?= htmlspecialchars($tecnico['nombre']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label>Tipo de Orden:</label>
      <select name="tipo" required>
        <option value="mantenimiento" <?= ($tipo == 'mantenimiento') ? 'selected' : '' ?>>Mantenimiento</option>
        <option value="instalacion" <?= ($tipo == 'instalacion') ? 'selected' : '' ?>>Instalación</option>
      </select>

      <label>Fecha:</label>
      <input type="date" name="fecha" value="<?= htmlspecialchars($fecha) ?>" required>

      <label>Hora Inicio:</label>
      <input type="time" name="hora_inicio" value="<?= htmlspecialchars($hora_inicio) ?>" required>

      <label>Hora Fin:</label>
      <input type="time" name="hora_fin" value="<?= htmlspecialchars($hora_fin) ?>" required>

      <label>Dirección:</label>
      <textarea name="observaciones"><?= htmlspecialchars($observaciones) ?></textarea>

      <label>Archivo actual:</label><br>
      <?php if (!empty($archivo)): ?>
        <a href="uploads/<?= htmlspecialchars($archivo) ?>" target="_blank">Ver archivo actual</a><br><br>
      <?php else: ?>
        No hay archivo.<br><br>
      <?php endif; ?>

      <label>Nuevo Archivo (opcional):</label>
      <input type="file" name="archivo" accept=".jpg,.jpeg,.png,.pdf">

      <button type="submit">Actualizar Orden</button>
    </form>
  </div>

</body>

</html>