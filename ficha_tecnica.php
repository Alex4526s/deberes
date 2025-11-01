<?php
session_start();
require 'db.php';
require 'fpdf/fpdf.php';

date_default_timezone_set('America/Guayaquil');
ob_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

define('SUCCESS_MESSAGE', isset($_GET['mensaje']) ? $_GET['mensaje'] : '');
$busqueda = '';
$queryParams = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['busqueda'])) {
    $busqueda = $_POST['busqueda'];
}

$horaActual = date('H:i');
$fechaActual = date('Y-m-d');

$query = "
SELECT 
    o.id, o.pedido, o.fecha, o.hora_inicio, o.hora_fin,
    t.nombre AS tecnico_nombre, t.celular,
    o.tipo, o.observaciones, o.archivo
FROM 
    ordenes o
LEFT JOIN 
    tecnicos t ON o.tecnico_id = t.id
WHERE 1=1
";

if (!empty($busqueda)) {
    $query .= " AND (o.pedido LIKE ? OR t.nombre LIKE ?)";
    $queryParams[] = "%$busqueda%";
    $queryParams[] = "%$busqueda%";
}

if (!empty($queryParams)) {
    $stmt = $conn->prepare($query);
    $types = str_repeat('s', count($queryParams));
    $stmt->bind_param($types, ...$queryParams);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    $resultado = $conn->query($query);
}

// 🛠️ Generar PDF
if (isset($_POST['generar_pdf'])) {
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    if (empty($fecha_inicio) || empty($fecha_fin)) {
        die("Error: Debes seleccionar un rango de fechas.");
    }

    $queryPDF = "
    SELECT o.pedido, o.fecha, o.hora_inicio, o.hora_fin, t.nombre AS tecnico_nombre, o.observaciones
    FROM ordenes o
    LEFT JOIN tecnicos t ON o.tecnico_id = t.id
    WHERE o.fecha BETWEEN ? AND ?
    ORDER BY o.fecha ASC
    ";

    $stmt = $conn->prepare($queryPDF);
    $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
    $stmt->execute();
    $resultado_pdf = $stmt->get_result();

    // 🛠️ Mejorar PDF
    $pdf = new FPDF('L', 'mm', 'A4');
    $pdf->AddPage();

    // Logo centrado
    $logoWidth = 240; // Un ancho más razonable para que no sobrepase
    $logoX = ($pdf->GetPageWidth() - $logoWidth) / 2;
    $pdf->Image('assets/ORDEN.png', $logoX, 10, $logoWidth);
    $pdf->Ln(30);

    // Rango de fechas
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, utf8_decode("Historial de órdenes del $fecha_inicio al $fecha_fin"), 0, 1, 'C');
    $pdf->Ln(5);

    // Encabezados tabla
    $pdf->SetFillColor(196, 0, 0); // Rojo oscuro
    $pdf->SetTextColor(255); // Texto blanco
    $pdf->SetDrawColor(180, 180, 180); // Líneas grises
    $pdf->SetFont('Arial', 'B', 10);

    // Anchos de columna mejor distribuidos
    $w = array(35, 30, 25, 25, 55, 95);

    // Encabezados
    $headers = array('Pedido', 'Fecha', 'Inicio', 'Fin', 'Técnico', 'Dirección');
    foreach ($headers as $i => $header) {
        $pdf->Cell($w[$i], 10, utf8_decode($header), 1, 0, 'C', true);
    }
    $pdf->Ln();

    // Restaurar color texto para filas
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial', '', 9);

    // Mostrar filas
    while ($orden = $resultado_pdf->fetch_assoc()) {
        $pdf->Cell($w[0], 10, utf8_decode($orden['pedido']), 1, 0, 'C');
        $pdf->Cell($w[1], 10, utf8_decode($orden['fecha']), 1, 0, 'C');
        $pdf->Cell($w[2], 10, utf8_decode($orden['hora_inicio']), 1, 0, 'C');
        $pdf->Cell($w[3], 10, utf8_decode($orden['hora_fin']), 1, 0, 'C');
        $pdf->Cell($w[4], 10, utf8_decode(substr($orden['tecnico_nombre'] ?? 'No asignado', 0, 30)), 1, 0, 'C');
        $pdf->Cell($w[5], 10, utf8_decode(substr($orden['observaciones'] ?? 'Sin Dirección', 0, 80)), 1, 0, 'L'); // Observaciones alineadas a la izquierda
        $pdf->Ln();
    }

    ob_end_clean();
    $pdf->Output('D', "Historial_Ordenes_{$fecha_inicio}_{$fecha_fin}.pdf");
    exit();

}

include 'views/ficha_tecnica_view.php';
?>