<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Historial de Usuarios - Hornipan</title>
    <link rel="stylesheet" href="assets/css/styleEditarTecnico.css">
    <link rel="stylesheet" href="assets/css/historial_usuarios.css">
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
        <h2>Historial de Usuarios</h2>

        <div class="form-card">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario['id']) ?></td>
                            <td><?= htmlspecialchars($usuario['usuario']) ?></td>
                            <td style="padding: 10px; text-align: center; border: 1px solid #ccc;">
                                <div style="display: flex; justify-content: center; align-items: center; gap: 5px;">
                                    <a href="editar_usuario.php?id=<?= $usuario['id'] ?>">
                                        <button class="btn-accion-pequeño">Editar</button>
                                    </a>
                                    <a href="eliminar_usuario.php?id=<?= $usuario['id'] ?>">
                                        <button class="btn-accion-pequeño">Eliminar</button>
                                    </a>
                                </div>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>