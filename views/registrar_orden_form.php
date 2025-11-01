<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Orden - Hornipan</title>
    <link rel="stylesheet" href="assets/css/obtener_ordenes.css">
    <script src="assets/js/registrar_orden.js" defer></script>
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

    <div class="content">
        <h2>Registrar Nueva Orden</h2>

        <?php if (!empty($mensaje)): ?>
            <div class="mensaje"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Técnico:</label>
            <select name="tecnico_id">
                <option value="">Seleccionar técnico</option>
                <?php while ($tec = $tecnicos->fetch_assoc()): ?>
                    <option value="<?= $tec['id'] ?>"><?= htmlspecialchars($tec['nombre']) ?></option>
                <?php endwhile; ?>
            </select>

            <label>Tipo:</label>
            <select name="tipo" required>
                <option value="">Seleccionar tipo</option>
                <option value="mantenimiento">Mantenimiento</option>
                <option value="instalacion">Instalación</option>
            </select>

            <label>Fecha:</label>
            <input type="date" name="fecha" required>

            <label>Hora de Inicio:</label>
            <input type="time" name="hora_inicio" required>

            <label>Hora de Fin:</label>
            <input type="time" name="hora_fin" required>

            <label>Archivo (opcional):</label>
            <input type="file" name="archivo" accept=".jpg,.jpeg,.png,.pdf">

            <button type="submit">Registrar Orden</button>
        </form>
    </div>

</body>

</html>