<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario'])) {
  header('Location: login.php');
  exit();
}

date_default_timezone_set('America/Guayaquil');
$horaActual = date('H:i');
$fechaActual = date('Y-m-d');

$diasSemana = [
  'Monday' => 'Lunes',
  'Tuesday' => 'Martes',
  'Wednesday' => 'Miércoles',
  'Thursday' => 'Jueves',
  'Friday' => 'Viernes',
  'Saturday' => 'Sábado',
  'Sunday' => 'Domingo'
];

$fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('monday this week'));
$fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d', strtotime('sunday this week'));

$inicioSemana = strtotime($fechaInicio);
$diasSemanaFechas = [];
foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $i => $dia) {
  $diasSemanaFechas[$dia] = date('Y-m-d', strtotime("+$i day", $inicioSemana));
}

$ordenesQuery = $conn->prepare("SELECT ordenes.*, tecnicos.nombre AS tecnico_nombre, ordenes.estado AS estado_nombre FROM ordenes LEFT JOIN tecnicos ON ordenes.tecnico_id = tecnicos.id WHERE fecha BETWEEN ? AND ?");
$ordenesQuery->bind_param('ss', $fechaInicio, $fechaFin);
$ordenesQuery->execute();
$resultado = $ordenesQuery->get_result();

$ordenesPorDiaHora = [];
$pendientes = $procesando = $finalizadas = 0;
$totalOrdenes = 0;

while ($orden = $resultado->fetch_assoc()) {
  $totalOrdenes++;
  $fechaOrden = $orden['fecha'];
  $diaIngles = date('l', strtotime($fechaOrden));
  $diaEspanol = $diasSemana[$diaIngles];
  $ordenesPorDiaHora[$diaEspanol][] = $orden;

  if ($fechaOrden < $fechaActual || ($fechaOrden == $fechaActual && $orden['hora_fin'] <= $horaActual)) {
    $finalizadas++;
  } elseif ($fechaOrden == $fechaActual && $orden['hora_inicio'] <= $horaActual && $orden['hora_fin'] > $horaActual) {
    $procesando++;
  } else {
    $pendientes++;
  }
}

$porcentajePendientes = $totalOrdenes > 0 ? round(($pendientes / $totalOrdenes) * 100) : 0;
$porcentajeProcesando = $totalOrdenes > 0 ? round(($procesando / $totalOrdenes) * 100) : 0;
$porcentajeFinalizadas = $totalOrdenes > 0 ? round(($finalizadas / $totalOrdenes) * 100) : 0;
include 'views/dashboard.php';
?>
