<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Orden - Hornipan</title>
    <link rel="stylesheet" href="assets/css/registrar_orden.css">
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

        <?php if (!empty($mensaje_error)): ?>
            <div id="errorMessage" class="error-message"><?= htmlspecialchars($mensaje_error) ?></div>
        <?php endif; ?>

        <?php if (!empty($mensaje_success)): ?>
            <div id="successMessage" class="success-message"><?= htmlspecialchars($mensaje_success) ?></div>
        <?php endif; ?>

        <?php if (!empty($mensaje_correo)): ?>
            <div class="success-message"><?= htmlspecialchars($mensaje_correo) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Código de Pedido:</label>
            <input type="text" name="pedido" placeholder="Ej: P0000000123" required>

            <label>Seleccione Técnico:</label>
            <select name="tecnico_id">
                <option value="">-- Sin técnico asignado --</option>
                <?php foreach ($tecnicos as $tecnico): ?>
                    <option value="<?= $tecnico['id'] ?>"><?= htmlspecialchars($tecnico['nombre']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Tipo de Orden:</label>
            <select name="tipo" required>
                <option value="">-- Seleccione --</option>
                <option value="mantenimiento">Mantenimiento</option>
                <option value="instalacion">Instalación</option>
            </select>

            <label>Fecha:</label>
            <input type="date" name="fecha" required>

            <label>Hora Inicio:</label>
            <input type="time" name="hora_inicio" required>

            <label>Hora Fin:</label>
            <input type="time" name="hora_fin" required>

            <label>Dirección:</label>
            <textarea name="observaciones" rows="3" placeholder="Dirección"></textarea>

            <label>Archivo (Foto o PDF):</label>
            <input type="file" name="archivo" accept=".jpg,.jpeg,.png,.pdf">

            <button type="submit">Registrar Orden</button>
        </form>
    </div>
    <!-- 🔥 Script para ocultar mensaje después de 5 segundos -->
    <script>
        setTimeout(function() {
            var mensaje = document.getElementById('mensaje');
            if (mensaje) {
                mensaje.style.opacity = 0;
                setTimeout(function() {
                    mensaje.style.display = 'none';
                }, 1000); // después del desvanecimiento
            }
        }, 5000);
    </script>
</body>

</html>