
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Hornipan</title>
  <link rel="stylesheet" href="assets/css/styleIndex.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const porcentajePendientes = <?= json_encode($porcentajePendientes) ?>;
    const porcentajeProcesando = <?= json_encode($porcentajeProcesando) ?>;
    const porcentajeFinalizadas = <?= json_encode($porcentajeFinalizadas) ?>;
  </script>
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
  <a href="ficha_tecnica.php" style="padding-left:40px;">📁 Historial de órdenes</a>
  <a href="historial_tecnicos.php" style="padding-left:40px;">📁 Historial de técnicos</a>
  <a href="historial_usuarios.php" style="padding-left:40px;">📁 Historial de usuarios</a>
</div>

<div class="content">
  <h2>Dashboard Hornipan</h2>

  <div class="stats">
    <div class="card">
      <canvas id="pendientesChart"></canvas>
      <h3>Pendientes: <?= $pendientes ?></h3>
    </div>
    <div class="card">
      <canvas id="procesandoChart"></canvas>
      <h3>Procesando: <?= $procesando ?></h3>
    </div>
    <div class="card">
      <canvas id="finalizadasChart"></canvas>
      <h3>Finalizadas: <?= $finalizadas ?></h3>
    </div>
  </div>

  <form method="GET" class="filtro-fechas" style="margin-bottom:20px; display:flex; gap:10px; align-items:center;">
    <label><strong>Desde:</strong></label>
    <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($fechaInicio) ?>" required>
    <label><strong>Hasta:</strong></label>
    <input type="date" name="fecha_fin" value="<?= htmlspecialchars($fechaFin) ?>" required>
    <button type="submit" style="background:#c40000; color:white; padding:8px 12px; border:none; border-radius:5px;">Filtrar</button>
  </form>

  <h2>Horario Semanal</h2>
  <table>
    <thead>
      <tr>
        <th>Hora</th>
        <?php foreach ($diasSemanaFechas as $dia => $fecha): ?>
          <th><?= $dia ?><br><small><?= $fecha ?></small></th>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
      <?php for ($h = 6; $h < 20; $h++): ?>
        <tr>
          <td class="hora"><?= sprintf("%02d:00", $h) ?> - <?= sprintf("%02d:00", $h+1) ?></td>
          <?php foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia): ?>
            <td class="vacio" style="position:relative;">
              <?php include 'views/horario_bloque.php'; ?>
            </td>
          <?php endforeach; ?>
        </tr>
      <?php endfor; ?>
    </tbody>
  </table>
</div>

<div id="modalOrden" class="modal">
  <div class="modal-content">
    <span onclick="cerrarModal()" class="close-btn">❌</span>
    <h2 id="modalTecnico"></h2>
    <p><strong>Pedido:</strong> <span id="modalPedido"></span></p>
    <p><strong>Tipo:</strong> <span id="modalTipo"></span></p>
    <p><strong>Horario:</strong> <span id="modalHoras"></span></p>
    <p><strong>Dirección:</strong> <span id="modalObservaciones"></span></p>
    <div id="modalArchivo" style="margin-top:20px;"></div>
  </div>
</div>

<script>
function abrirModal(tecnico, pedido, tipo, horas, direccion, archivoUrl = '') {
  document.getElementById('modalTecnico').innerText = tecnico;
  document.getElementById('modalPedido').innerText = pedido;
  document.getElementById('modalTipo').innerText = tipo;
  document.getElementById('modalHoras').innerText = horas;
  document.getElementById('modalObservaciones').innerText = direccion || 'Sin observaciones';

  if (archivoUrl) {
    const ext = archivoUrl.split('.').pop().toLowerCase();
    if (['jpg', 'jpeg', 'png'].includes(ext)) {
      document.getElementById('modalArchivo').innerHTML = `<img src="uploads/${archivoUrl}" style="max-width:100%;">`;
    } else if (ext === 'pdf') {
      document.getElementById('modalArchivo').innerHTML = `<iframe src="uploads/${archivoUrl}" width="100%" height="400px" style="border:none;"></iframe>`;
    } else {
      document.getElementById('modalArchivo').innerHTML = 'Archivo no disponible.';
    }
  } else {
    document.getElementById('modalArchivo').innerHTML = 'Sin archivo adjunto.';
  }

  document.getElementById('modalOrden').style.display = 'flex';
}

function cerrarModal() {
  document.getElementById('modalOrden').style.display = 'none';
}

function crearGraficoCircular(idCanvas, porcentaje, titulo, color) {
  const ctx = document.getElementById(idCanvas).getContext('2d');
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: [titulo, 'Restante'],
      datasets: [{
        data: [porcentaje, 100 - porcentaje],
        backgroundColor: [color, '#e0e0e0'],
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      cutout: '70%',
      plugins: { legend: { display: false } }
    }
  });
}

document.addEventListener('DOMContentLoaded', function() {
  crearGraficoCircular('pendientesChart', porcentajePendientes, 'Pendientes', '#c40000');
  crearGraficoCircular('procesandoChart', porcentajeProcesando, 'Procesando', '#ffa500');
  crearGraficoCircular('finalizadasChart', porcentajeFinalizadas, 'Finalizadas', '#4caf50');
});
</script>

</body>
</html>
