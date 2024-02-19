<?php

// Importa las clases necesarias de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Requiere los archivos de PHPMailer necesarios
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
require '../phpmailer/src/Exception.php';

// Crea una instancia de PHPMailer, permitiendo excepciones (true)
$mail = new PHPMailer(true);

// Configura opciones para el servidor SMTP
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);

try {
    // Configuración del servidor SMTP
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;      // Habilita la salida de depuración del servidor SMTP (para depuración)
    $mail->isSMTP();                           // Usa SMTP para enviar el correo
    $mail->Host       = 'smtp.gmail.com';      // Servidor SMTP de Gmail
    $mail->SMTPAuth   = true;                  // Habilita la autenticación SMTP
    $mail->Username   = 'nestorsammyescobar@gmail.com';   // Nombre de usuario SMTP
    $mail->Password   = 'oxyqoxwgudthwror';   // Contraseña SMTP
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Habilita la encriptación TLS
    $mail->Port       = 587;                   // Puerto SMTP para la conexión (587 para TLS)

    // Configuración de los destinatarios
    $mail->setFrom('nestorsammyescobar@gmail.com', 'Tienda S.T.I.T.');  // Dirección de correo remitente
    $mail->addAddress('nestorsammyescobar@gmail.com', 'Joe User');     // Agrega un destinatario

    // Genera un ID único para la transacción
    $id_transaccion = uniqid();

    // Configuración del contenido del correo
    $mail->isHTML(true);  // Configura el formato del correo como HTML
    $mail->Subject = 'Detalles de su compra';  // Asunto del correo

    $cuerpo = '<h4>Gracias por su compra</h4>';  // Cuerpo del correo (mensaje)
    $cuerpo .= '<p>el ID de su compra es: <b>' . $id_transaccion . '</b></p>';
    var_dump($cuerpo); // Agregar esta línea para depurar

    // Convierte el cuerpo a UTF-8 para evitar problemas de codificación
    $mail->Body = mb_convert_encoding($cuerpo, 'UTF-8', 'ISO-8859-1');
    $mail->AltBody = 'Le enviamos los detalles de su compra.'; // Texto alternativo (no HTML)

    // Configura el idioma del correo (en español)
    $mail->setLanguage('es', '../phpmailer/language/phpmailer.lang-es.php');

    // Envía el correo
    $mail->send();
} catch (Exception $e) {
    echo "Error al enviar el correo electrónico de la compra: {$mail->ErrorInfo}";
    exit;
}
