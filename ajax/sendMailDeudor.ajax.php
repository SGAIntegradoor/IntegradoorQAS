<?php

$rutaAbsoluta = dirname(__DIR__) . '/libraries/src';
require_once $rutaAbsoluta . '/PHPMailer.php';
require_once $rutaAbsoluta . '/SMTP.php';
require_once $rutaAbsoluta . '/Exception.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$data = json_decode(file_get_contents("php://input"), true);

function sendEmail()
{
    global $data;
    // Recibir y decodificar JSON desde el frontend
    $receiptMail = "tecnologia@grupoasistencia.com";

    // Configuración SMTP
    $mail = new PHPMailer(true); // Habilitar excepciones para mejor manejo de errores
    try {
        $mail->isSMTP();
        $mail->Host = 'strategico.tech';
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';
        $mail->SMTPAuth = true;
        $mail->Username = 'notificaciones@strategico.tech';
        $mail->Password = 'Sga.Tecno2024*';

        // Configurar el remitente y destinatario del correo
        $mail->setFrom('notificaciones@strategico.tech', 'Equipo Integradoor');
        $mail->addAddress($receiptMail, 'Cliente');

        // Configuración asunto y cuerpo del correo
        $mail->Subject = 'Aviso: Cotización de Seguro de Vida Deudor';
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);

        // Cuerpo del correo
        $mail->Body = '
        <html>
        <head>
            <title>Cotización de Seguro</title>
        </head>
        <body>
            <h2>Hola, el Asesor ' . htmlspecialchars($data["asesor"]) . ',</h2>
            <p>Documento Asesor: ' . htmlspecialchars($data["documentoAsesor"]) . '</p>
            <p>Email Asesor: ' . htmlspecialchars($data["correoAsesor"]) . '</p>
            <h2>Ha solicitado una cotización de seguro de vida deudor para el cliente ' . htmlspecialchars($data["clienteNombre"]) . ' ' . htmlspecialchars($data["clienteApellido"]) . '</h2>
            <p>Documento Cliente: ' . htmlspecialchars($data["clienteDocumento"]) . '</p>
            <p>Email Cliente: ' . htmlspecialchars($data["clienteCorreo"]) . '</p>
            <p>Teléfono Cliente: ' . htmlspecialchars($data["clienteCelular"]) . '</p>
            <p style="color:#2e2e2e;">Nota: este mail solamente lo recibe el analista y no el cliente o asesor.</p>
        </body>
        </html>';

        // Intentar enviar el correo
        if ($mail->send()) {
            echo json_encode(["success" => true, "message" => "Correo enviado exitosamente."]);
        } else {
            echo json_encode(["success" => false, "message" => "El correo no pudo ser enviado."]);
        }

    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Error al enviar el correo: " . $mail->ErrorInfo]);
    }
}


if(isset($data["asesor"])){
    sendEmail();
} else {
    echo json_encode(["success" => false, "message" => "Error al enviar el correo: Datos no recibidos."]);
}
