<?php
if (isset($ordenesPorDiaHora[$dia])) {
  $ordenesEnHora = [];

  foreach ($ordenesPorDiaHora[$dia] as $orden) {
    $inicioHora = (int) substr($orden['hora_inicio'], 0, 2);
    $inicioMin = (int) substr($orden['hora_inicio'], 3, 2);
    $finHora = (int) substr($orden['hora_fin'], 0, 2);
    $finMin = (int) substr($orden['hora_fin'], 3, 2);

    $inicioTotalMin = $inicioHora * 60 + $inicioMin;
    $finTotalMin = $finHora * 60 + $finMin;

    $bloqueInicioMin = $h * 60;
    $bloqueFinMin = ($h + 1) * 60;

    // Verificar si la orden cruza este bloque horario
    if ($inicioTotalMin < $bloqueFinMin && $finTotalMin > $bloqueInicioMin) {
      $ordenesEnHora[] = $orden;
    }
  }

  if (count($ordenesEnHora) > 0) {
    $ordenWidth = 100 / count($ordenesEnHora);

    foreach ($ordenesEnHora as $index => $orden):
      $inicioHora = (int) substr($orden['hora_inicio'], 0, 2);
      $inicioMin = (int) substr($orden['hora_inicio'], 3, 2);
      $finHora = (int) substr($orden['hora_fin'], 0, 2);
      $finMin = (int) substr($orden['hora_fin'], 3, 2);

      $inicioTotalMin = $inicioHora * 60 + $inicioMin;
      $finTotalMin = $finHora * 60 + $finMin;

      $bloqueInicioMin = $h * 60;
      $bloqueFinMin = ($h + 1) * 60;

      $top = max(0, ($inicioTotalMin - $bloqueInicioMin) / 60 * 100);
      $height = min(100, ($finTotalMin - max($inicioTotalMin, $bloqueInicioMin)) / 60 * 100);

      $color = 'gray';
      $colorTexto = 'white';

      $horaActualMin = (int) date('H') * 60 + (int) date('i');
      $fechaOrden = $orden['fecha'];
      $fechaOrdenTimestamp = strtotime($fechaOrden);
      $fechaActualTimestamp = strtotime($fechaActual);

      if ($fechaOrdenTimestamp > $fechaActualTimestamp || ($fechaOrdenTimestamp == $fechaActualTimestamp && $inicioTotalMin > $horaActualMin)) {
        $color = 'red'; // Pendiente
      } elseif ($fechaOrdenTimestamp == $fechaActualTimestamp && $horaActualMin >= $inicioTotalMin && $horaActualMin <= $finTotalMin) {
        $color = 'orange'; // Procesando
        $colorTexto = 'black';
      } elseif ($fechaOrdenTimestamp < $fechaActualTimestamp || $horaActualMin > $finTotalMin) {
        $color = 'green'; // Finalizado
      }

      $tecnico = htmlspecialchars($orden['tecnico_nombre'] ?? 'Sin Técnico');
      if ($tecnico === 'Sin Técnico') {
        $color = '#d3d3d3';
        $colorTexto = 'black';
      }

      $pedido = htmlspecialchars($orden['pedido'] ?? '');
      $tipo = htmlspecialchars($orden['tipo'] ?? '');
      $horario = htmlspecialchars($orden['hora_inicio']) . ' - ' . htmlspecialchars($orden['hora_fin']);
      $direccion = htmlspecialchars($orden['observaciones'] ?? '');
      $archivo = htmlspecialchars($orden['archivo'] ?? '');
?>
<div style="position:absolute;
            top:<?= $top ?>%;
            left:<?= ($index * $ordenWidth) ?>%;
            width:<?= $ordenWidth ?>%;
            height:<?= $height ?>%;
            background:<?= $color ?>;
            color:<?= $colorTexto ?>;
            font-size:10px;
            display:flex;
            justify-content:center;
            align-items:center;
            cursor:pointer;
            border:<?= ($tecnico === 'Sin Técnico') ? '2px dashed black' : 'none' ?>;"
     onclick="abrirModal('<?= $tecnico ?>', '<?= $pedido ?>', '<?= $tipo ?>', '<?= $horario ?>', '<?= $direccion ?>', '<?= $archivo ?>')">
  <?= ($tecnico === 'Sin Técnico') ? '👤 Sin Técnico' : $tecnico ?>
</div>
<?php
    endforeach;
  }
}
?>
