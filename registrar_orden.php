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

// Obtener técnicos
$tecnicos = [];
$result = $conn->query("SELECT id, nombre FROM tecnicos");
while ($row = $result->fetch_assoc()) {
    $tecnicos[] = $row;
}

// Inicializar mensajes
$mensaje_error = '';
$mensaje_success = '';
$mensaje_correo = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tecnico_id = !empty($_POST['tecnico_id']) ? $_POST['tecnico_id'] : null;
    $tipo = $_POST['tipo'];
    $fecha = $_POST['fecha'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];
    $observaciones = $_POST['observaciones'] ?? '';
    $pedido = $_POST['pedido'];

    if (empty($fecha) || empty($hora_inicio) || empty($hora_fin) || empty($tipo) || empty($pedido)) {
        die("❌ Error: Todos los campos obligatorios deben estar llenos.");
    }

    // Verificar pedido duplicado
    $stmt_pedido = $conn->prepare("SELECT * FROM ordenes WHERE pedido = ?");
    $stmt_pedido->bind_param("s", $pedido);
    $stmt_pedido->execute();
    $resultado_pedido = $stmt_pedido->get_result();

    if ($resultado_pedido->num_rows > 0) {
        $mensaje_error = "❌ Error: El pedido ya ha sido ingresado.";
    } else {
        // Verificar que el técnico no esté ocupado (solo si hay técnico seleccionado)
        if (!empty($tecnico_id)) {
            $stmt_tecnico_ocupado = $conn->prepare("
                SELECT * FROM ordenes 
                WHERE tecnico_id = ? 
                AND fecha = ? 
                AND ((hora_inicio BETWEEN ? AND ?) OR (hora_fin BETWEEN ? AND ?))
            ");
            $stmt_tecnico_ocupado->bind_param("isssss", $tecnico_id, $fecha, $hora_inicio, $hora_fin, $hora_inicio, $hora_fin);
            $stmt_tecnico_ocupado->execute();
            $resultado_ocupado = $stmt_tecnico_ocupado->get_result();

            if ($resultado_ocupado->num_rows > 0) {
                $mensaje_error = "❌ El técnico está ocupado en ese horario.";
            }
        }

        // Si no hubo error hasta aquí, registramos la orden
        if (empty($mensaje_error)) {
            $archivoNombre = "";
            if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
                $archivoNombre = time() . "_" . basename($_FILES["archivo"]["name"]);
                move_uploaded_file($_FILES["archivo"]["tmp_name"], "uploads/" . $archivoNombre);
            }

            $stmt = $conn->prepare("
                INSERT INTO ordenes (estado, fecha, hora_inicio, hora_fin, tecnico_id, tipo, observaciones, pedido, archivo)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $estado = 'Pendiente';
            $stmt->bind_param("ssssissss", $estado, $fecha, $hora_inicio, $hora_fin, $tecnico_id, $tipo, $observaciones, $pedido, $archivoNombre);

            if ($stmt->execute()) {
                $mensaje_success = "✅ Orden registrada exitosamente.";

                // Si hay técnico, buscar su correo
                if (!empty($tecnico_id)) {
                    $stmt_tecnico = $conn->prepare("SELECT correo FROM tecnicos WHERE id = ?");
                    $stmt_tecnico->bind_param("i", $tecnico_id);
                    $stmt_tecnico->execute();
                    $resultado_tecnico = $stmt_tecnico->get_result();
                    $tecnico = $resultado_tecnico->fetch_assoc();
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
                            $mail->Subject = 'Nueva Orden Registrada';
                            $mail->Body = '
                            <!DOCTYPE html>
                            <html lang="es">
                            <head>
                                <meta charset="UTF-8">
                            </head>
                            <body style="font-family: Arial, sans-serif; background-color: #f7f7f7; padding: 20px;">
                                <div style="max-width: 600px; margin: auto; background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1);">
                                    <h2 style="color: #c40000; text-align: center;">🚚 Nueva Orden Registrada</h2>
                                    <p style="font-size: 16px;">Se ha registrado una nueva orden con los siguientes datos:</p>
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
                                    <p style="margin-top: 30px; font-size: 14px; color: #777;">Este correo fue generado automáticamente por el sistema de órdenes de <strong>Hornipan</strong>.</p>
                                </div>
                            </body>
                            </html>';

                            if (!empty($archivoNombre)) {
                                $mail->addAttachment("uploads/$archivoNombre");
                            }

                            $mail->send();
                            $mensaje_correo = "✅ Correo enviado correctamente.";
                        } catch (Exception $e) {
                            $mensaje_correo = "❌ Error al enviar el correo: {$mail->ErrorInfo}";
                        }
                    } else {
                        $mensaje_correo = "❌ No se encontró el correo del técnico.";
                    }
                } else {
                    $mensaje_correo = "⛔ No se seleccionó técnico para enviar correo.";
                }
            } else {
                $mensaje_error = "❌ Error al registrar la orden.";
            }

            $stmt->close();
        }
    }
}

include 'views/registrar_orden_view.php';
?>
