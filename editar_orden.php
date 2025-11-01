<?php 
session_start();
require 'db.php';
require 'vendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verificar autenticación
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

// Obtener técnicos para el select
$tecnicos = [];
$result = $conn->query("SELECT id, nombre FROM tecnicos");
while ($row = $result->fetch_assoc()) {
    $tecnicos[] = $row;
}

// Verificar que viene ID de la orden
if (!isset($_GET['id'])) {
    echo "❌ ID de orden no especificado.";
    exit();
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM ordenes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$orden = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$orden) {
    echo "❌ Orden no encontrada.";
    exit();
}

// Asignar datos iniciales
$pedido = $orden['pedido'];
$tecnico_id = $orden['tecnico_id'];
$tipo = $orden['tipo'];
$fecha = $orden['fecha'];
$hora_inicio = $orden['hora_inicio'];
$hora_fin = $orden['hora_fin'];
$observaciones = $orden['observaciones'];
$archivo = $orden['archivo'];

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pedido = $_POST['codigo_pedido'];
    $tecnico_id = $_POST['tecnico_id'];
    $tipo = $_POST['tipo'];
    $fecha = $_POST['fecha'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];
    $observaciones = $_POST['observaciones'] ?? '';
    $archivoNombre = $archivo;

    if ($hora_inicio >= $hora_fin) {
        echo "❌ Error: La hora de inicio debe ser menor que la hora de fin.";
        exit();
    }

    // Verificar si el código de pedido ya existe en otra orden
    $stmt_verificar = $conn->prepare("SELECT id FROM ordenes WHERE pedido = ? AND id != ?");
    $stmt_verificar->bind_param("si", $pedido, $id);
    $stmt_verificar->execute();
    $result_verificar = $stmt_verificar->get_result();

    if ($result_verificar->num_rows > 0) {
        echo "<!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <title>Error Pedido Duplicado</title>
            <script>
                setTimeout(function() {
                    window.history.back();
                }, 3000);
            </script>
        </head>
        <body style='font-family: Arial, sans-serif; text-align: center; padding-top: 100px;'>
            <h2 style='color: red;'>❌ Error: El número de pedido ya está registrado en otra orden.</h2>
            <p>Regresando al formulario en 3 segundos...</p>
        </body>
        </html>";
        exit();
    }
    $stmt_verificar->close();

    // Verificar si el técnico ya está ocupado en ese horario
    $stmt_cruce = $conn->prepare("
        SELECT id FROM ordenes 
        WHERE tecnico_id = ? 
          AND fecha = ? 
          AND id != ?
          AND (hora_inicio < ? AND hora_fin > ?)
    ");
    $stmt_cruce->bind_param("isiss", $tecnico_id, $fecha, $id, $hora_fin, $hora_inicio);
    $stmt_cruce->execute();
    $result_cruce = $stmt_cruce->get_result();

    if ($result_cruce->num_rows > 0) {
        echo "<!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <title>Error Técnico Ocupado</title>
            <script>
                setTimeout(function() {
                    window.history.back();
                }, 3000);
            </script>
        </head>
        <body style='font-family: Arial, sans-serif; text-align: center; padding-top: 100px;'>
            <h2 style='color: orange;'>⚠️ Error: El técnico ya tiene otra orden en ese horario.</h2>
            <p>Regresando al formulario en 3 segundos...</p>
        </body>
        </html>";
        exit();
    }
    $stmt_cruce->close();

    // Subida de archivo si se adjunta uno nuevo
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === 0) {
        $archivoNombre = time() . "_" . basename($_FILES["archivo"]["name"]);
        move_uploaded_file($_FILES["archivo"]["tmp_name"], "uploads/" . $archivoNombre);
    }

    // Actualizar la orden
    $stmt = $conn->prepare("
        UPDATE ordenes
        SET pedido = ?, tecnico_id = ?, tipo = ?, fecha = ?, hora_inicio = ?, hora_fin = ?, archivo = ?, observaciones = ?
        WHERE id = ?
    ");
    $stmt->bind_param("sissssssi", $pedido, $tecnico_id, $tipo, $fecha, $hora_inicio, $hora_fin, $archivoNombre, $observaciones, $id);

    if ($stmt->execute()) {
        // Buscar correo del técnico
        $stmt_tecnico = $conn->prepare("SELECT correo FROM tecnicos WHERE id = ?");
        $stmt_tecnico->bind_param("i", $tecnico_id);
        $stmt_tecnico->execute();
        $tecnico = $stmt_tecnico->get_result()->fetch_assoc();
        $stmt_tecnico->close();

        if ($tecnico && !empty($tecnico['correo'])) {
            $tecnico_email = $tecnico['correo'];

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'alexcaisalitin11@gmail.com';
                $mail->Password = 'skdf ispt vwvs roay';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->CharSet = 'UTF-8';

                $mail->setFrom('alexcaisalitin11@gmail.com', 'HORNIPAN');
                $mail->addAddress($tecnico_email);

                $mail->isHTML(true);
                $mail->Subject = 'Orden Actualizada';
                $mail->Body = '
                <!DOCTYPE html>
                <html lang="es">
                <head>
                    <meta charset="UTF-8">
                </head>
                <body style="font-family: Arial, sans-serif; background-color: #f7f7f7; padding: 20px;">
                    <div style="max-width: 600px; margin: auto; background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1);">
                        <h2 style="color: #c40000; text-align: center;">🚚 Orden Actualizada</h2>
                        <p style="font-size: 16px;">Estimado técnico, se ha actualizado una orden con los siguientes detalles:</p>
                        <table style="width: 100%; font-size: 16px; margin-top: 20px;">
                            <tr>
                                <td style="padding: 8px;"><strong>Pedido:</strong></td>
                                <td style="padding: 8px;">' . htmlspecialchars($pedido) . '</td>
                            </tr>
                            <tr style="background-color: #f2f2f2;">
                                <td style="padding: 8px;"><strong>Fecha:</strong></td>
                                <td style="padding: 8px;">' . htmlspecialchars($fecha) . '</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px;"><strong>Hora Inicio:</strong></td>
                                <td style="padding: 8px;">' . htmlspecialchars($hora_inicio) . '</td>
                            </tr>
                            <tr style="background-color: #f2f2f2;">
                                <td style="padding: 8px;"><strong>Hora Fin:</strong></td>
                                <td style="padding: 8px;">' . htmlspecialchars($hora_fin) . '</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px;"><strong>Tipo:</strong></td>
                                <td style="padding: 8px;">' . htmlspecialchars($tipo) . '</td>
                            </tr>
                            <tr style="background-color: #f2f2f2;">
                                <td style="padding: 8px;"><strong>Dirección:</strong></td>
                                <td style="padding: 8px;">' . nl2br(htmlspecialchars($observaciones)) . '</td>
                            </tr>
                        </table>
                        <p style="margin-top: 30px; font-size: 14px; color: #777;">Este correo fue generado automáticamente por el sistema de gestión de órdenes de <strong>Hornipan</strong>.</p>
                    </div>
                </body>
                </html>';

                if (!empty($archivoNombre)) {
                    $mail->addAttachment("uploads/" . $archivoNombre);
                }

                $mail->send();
            } catch (Exception $e) {
                // Error al enviar correo
            }
        }

        // Mostrar mensaje de éxito y redirigir
        echo "<!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <title>Orden Actualizada</title>
            <script>
                setTimeout(function() {
                    window.location.href = 'ficha_tecnica.php';
                }, 2500);
            </script>
        </head>
        <body style='font-family: Arial, sans-serif; text-align: center; padding-top: 100px;'>
            <h2 style='color: green;'>✅ Orden actualizada y correo enviado correctamente.</h2>
            <p>Redirigiendo a Ficha Técnica...</p>
        </body>
        </html>";
        exit();
    } else {
        echo "❌ Error al actualizar la orden.";
        exit();
    }

    $stmt->close();
}

// Cargar formulario
include 'views/editar_orden_form.php';
?>
