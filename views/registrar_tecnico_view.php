<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Técnico - Hornipan</title>
    <link rel="stylesheet" href="assets/css/registrar_tecnico.css">
    <link rel="stylesheet" href="assets/css/styleIndex.css">
    <script src="assets/js/registrar_tecnico.js" defer></script>
    <link rel="stylesheet" href="assets/css/mesanje.css">
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
        <h2>Registrar Técnico</h2>

        <!-- 🔥 Mensaje de alerta -->
        <?php if (!empty($mensaje)): ?>
            <div id="mensaje" class="alert <?= strpos($mensaje, '✅') !== false ? 'success' : 'error' ?>">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <label>Nombre del Técnico:</label>
            <input type="text" name="nombre" required>

            <label>Correo Electrónico:</label>
            <input type="email" name="correo" required>

            <label>Celular:</label>
            <input type="tel" name="celular" required>

            <button type="submit">Registrar Técnico</button>
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
