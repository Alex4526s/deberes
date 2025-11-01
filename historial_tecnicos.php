<?php
session_start();
require 'db.php';
require 'fpdf/fpdf.php';

ob_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

$busqueda = '';
$queryParams = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['busqueda'])) {
    $busqueda = $_POST['busqueda'];
}

$query = "SELECT * FROM tecnicos WHERE 1=1 ";

if (!empty($busqueda)) {
    $query .= "AND (nombre LIKE ? OR celular LIKE ?)";
    $queryParams[] = '%' . $busqueda . '%';
    $queryParams[] = '%' . $busqueda . '%';
}

if (!empty($queryParams)) {
    $stmt = $conn->prepare($query);
    $types = str_repeat('s', count($queryParams));
    $stmt->bind_param($types, ...$queryParams);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    $resultado = $conn->query("SELECT * FROM tecnicos");
}

// 🛠️ Generar PDF
if (isset($_POST['generar_pdf'])) {
    ob_start();

    $pdf = new FPDF('L', 'mm', 'A4');
    $pdf->AddPage();

    // Logo centrado
    $logoWidth = 240;
    $logoX = ($pdf->GetPageWidth() - $logoWidth) / 2;
    $pdf->Image('assets/TC.png', $logoX, 10, $logoWidth);
    $pdf->Ln(40);

    // Título
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, utf8_decode("Listado de Técnicos"), 0, 1, 'C');
    $pdf->Ln(10);

    // Encabezados de tabla
    $pdf->SetFillColor(196, 0, 0); // Fondo rojo
    $pdf->SetTextColor(255); // Letras blancas
    $pdf->SetDrawColor(180, 180, 180); // Bordes grises
    $pdf->SetFont('Arial', 'B', 10);

    $pdf->Cell(30, 10, utf8_decode('ID'), 1, 0, 'C', true);
    $pdf->Cell(60, 10, utf8_decode('Nombre'), 1, 0, 'C', true);
    $pdf->Cell(70, 10, utf8_decode('Correo'), 1, 0, 'C', true);
    $pdf->Cell(40, 10, utf8_decode('Celular'), 1, 1, 'C', true);

    // Restaurar color texto normal
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial', '', 10);

    while ($tecnico = $resultado->fetch_assoc()) {
        $pdf->Cell(30, 10, utf8_decode($tecnico['id']), 1, 0, 'C');
        $pdf->Cell(60, 10, utf8_decode($tecnico['nombre']), 1, 0, 'C');
        $pdf->Cell(70, 10, utf8_decode($tecnico['correo']), 1, 0, 'C');
        $pdf->Cell(40, 10, utf8_decode($tecnico['celular']), 1, 1, 'C');
    }

    ob_end_clean();
    $pdf->Output('D', 'Listado_Tecnicos.pdf');
    exit();
}

include 'views/historial_tecnicos_view.php';
?>
