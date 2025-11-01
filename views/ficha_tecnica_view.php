<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Historial de Órdenes - Hornipan</title>
    <link rel="stylesheet" href="assets/css/styleHistorialOrdenes.css">
    <script src="assets/js/ficha_tecnica.js" defer></script>
    <link rel="stylesheet" href="assets/css/ficha_tecnica_view.css">


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
        <h2>Historial de Órdenes</h2>

        <?php if (SUCCESS_MESSAGE): ?>
            <div class="alert success">
                ✅ <?= htmlspecialchars(SUCCESS_MESSAGE) ?>
            </div>
        <?php endif; ?>

        <div class="contenedor-busqueda">
            <form method="POST">
                <input type="text" name="busqueda" placeholder="Buscar pedido o técnico"
                    value="<?= htmlspecialchars($busqueda) ?>">
                <button type="submit">Buscar</button>
            </form>

            <form method="POST" class="form-pdf">
                <label>Desde:</label>
                <input type="date" name="fecha_inicio" required>
                <label>Hasta:</label>
                <input type="date" name="fecha_fin" required>
                <button type="submit" name="generar_pdf">Generar PDF</button>
            </form>
        </div>

        <div class="tabla-historial-container">
            <table class="tabla-historial">
                <thead>
                    <tr>
                        <th>Pedido</th>
                        <th>Fecha</th>
                        <th>Hora Inicio</th>
                        <th>Hora Fin</th>
                        <th>Técnico</th>
                        <th>Celular</th>
                        <th>Tipo</th>
                        <th>Dirección</th>
                        <th>Archivo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($orden = $resultado->fetch_assoc()): ?>
                        <?php
                        $estado = '';
                        if ($orden['fecha'] > $fechaActual) {
                            $estado = 'Pendiente';
                        } elseif ($orden['fecha'] == $fechaActual && $orden['hora_inicio'] > $horaActual) {
                            $estado = 'Pendiente';
                        } elseif ($orden['fecha'] == $fechaActual && $orden['hora_inicio'] <= $horaActual && $orden['hora_fin'] > $horaActual) {
                            $estado = 'En Proceso';
                        } elseif ($orden['fecha'] < $fechaActual || ($orden['fecha'] == $fechaActual && $orden['hora_fin'] <= $horaActual)) {
                            $estado = 'Finalizado';
                        }
                        ?>
                        <tr>
                            <td data-label="Pedido"><?= htmlspecialchars($orden['pedido']) ?></td>
                            <td data-label="Fecha"><?= htmlspecialchars($orden['fecha']) ?></td>
                            <td data-label="Hora Inicio"><?= htmlspecialchars($orden['hora_inicio']) ?></td>
                            <td data-label="Hora Fin"><?= htmlspecialchars($orden['hora_fin']) ?></td>
                            <td data-label="Técnico"><?= htmlspecialchars($orden['tecnico_nombre'] ?? 'No asignado') ?></td>
                            <td data-label="Celular"><?= htmlspecialchars($orden['celular'] ?? 'No disponible') ?></td>
                            <td data-label="Tipo"><?= htmlspecialchars($orden['tipo']) ?></td>
                            <td data-label="Dirección"><?= htmlspecialchars($orden['observaciones']) ?></td>
                            <td data-label="Archivo">
                                <?php if (!empty($orden['archivo'])): ?>
                                    <a href="uploads/<?= htmlspecialchars($orden['archivo']) ?>" target="_blank">Ver Archivo</a>
                                <?php else: ?>
                                    Sin archivo
                                <?php endif; ?>
                            </td>
                            <td data-label="Estado" class="<?= strtolower($estado) ?>"><?= htmlspecialchars($estado) ?></td>
                            <td data-label="Acciones">
                                <a href="editar_orden.php?id=<?= $orden['id'] ?>"><button>Editar</button></a>
                                <a href="eliminar_orden.php?id=<?= $orden['id'] ?>"><button>Eliminar</button></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>

</html>