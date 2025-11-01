<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Historial de Técnicos</title>
    <link rel="stylesheet" href="assets/css/HistorialTecnicos.css">
    <script src="assets/js/historial_tecnicos.js" defer></script>
    <style>
        .barra-superior {
            background-color: #c40000;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 60px;
        }

        /* Izquierda */
        .barra-izquierda img {
            height: 40px;
            background: white;
            padding: 5px;
            border-radius: 8px;
        }

        /* Derecha */
        .barra-derecha {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .usuario {
            font-size: 16px;
        }

        .enlace-salir {
            color: white;
            font-weight: bold;
            text-decoration: underline;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .enlace-salir:hover {
            color: #ffd6d6;
        }

        /* Responsivo */
        @media (max-width: 600px) {
            .barra-superior {
                flex-direction: column;
                height: auto;
                padding: 15px;
                gap: 10px;
            }

            .barra-derecha {
                justify-content: center;
            }
        }
    </style>
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
        <h2>Historial de Técnicos</h2>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="mensaje <?= $_SESSION['mensaje_tipo'] ?>">
                <?= htmlspecialchars($_SESSION['mensaje']) ?>
            </div>
            <?php unset($_SESSION['mensaje'], $_SESSION['mensaje_tipo']); ?>
        <?php endif; ?>

        <div class="busqueda">
            <form method="POST">
                <input type="text" name="busqueda" placeholder="Buscar por nombre o celular"
                    value="<?= htmlspecialchars($busqueda) ?>">
                <button type="submit">Buscar</button>
            </form>
        </div>

        <form method="POST" style="margin-bottom:20px;">
            <button type="submit" name="generar_pdf" class="btn-pdf">Generar PDF</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Celular</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($tecnico = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($tecnico['id']) ?></td>
                        <td><?= htmlspecialchars($tecnico['nombre']) ?></td>
                        <td><?= htmlspecialchars($tecnico['correo']) ?></td>
                        <td><?= htmlspecialchars($tecnico['celular']) ?></td>
                        <td>
                            <a href="editar_tecnico.php?id=<?= $tecnico['id'] ?>"><button>Editar</button></a>
                            <a href="eliminar_tecnico.php?id=<?= $tecnico['id'] ?>"><button>Eliminar</button></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>

</html>