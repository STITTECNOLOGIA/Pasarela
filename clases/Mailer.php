<?php

// Importa las clases necesarias de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Definición de la clase Mailer
class Mailer
{
    // Método para enviar un correo electrónico
    function enviarEmail($email, $asunto, $cuerpo)
    {

        // Incluye los archivos de configuración y las clases de PHPMailer
        require_once __DIR__ . '/../config/config.php';
        require __DIR__ . '/../phpmailer/src/PHPMailer.php';
        require __DIR__ . '/../phpmailer/src/SMTP.php';
        require __DIR__ . '/../phpmailer/src/Exception.php';

        // Crea una instancia de PHPMailer; pasa `true` para habilitar excepciones
        $mail = new PHPMailer(true);

        // Configura opciones de SMTP para permitir conexiones sin verificar certificados (¡cuidado en producción!)
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        try {
            // Configuración del servidor SMTP
            $mail->SMTPDebug = SMTP::DEBUG_OFF;      // Cambia a SMTP::DEBUG_OFF en producción; activa la salida de depuración detallada
            $mail->isSMTP();                                            // Envío utilizando SMTP
            $mail->Host       = 'smtp.gmail.com';                     // Servidor SMTP
            $mail->SMTPAuth   = true;                                   // Habilita la autenticación SMTP
            $mail->Username   = 'nestorsammyescobar@gmail.com';                     // Usuario SMTP
            $mail->Password   = 'oxyqoxwgudthwror';                               // Contraseña SMTP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            // Habilita cifrado TLS
            $mail->Port       = 587;                                    // Puerto TCP para conectar; usa 587 si has configurado `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            // Configuración de destinatarios
            $mail->setFrom('nestorsammyescobar@gmail.com', 'Tienda S.T.I.T.'); // Dirección de correo y nombre del remitente
            $mail->addAddress($email);     // Agrega un destinatario

            // Genera un ID de transacción único (puedes personalizar esto según tus necesidades)
            $id_transaccion = uniqid();

            // Configuración del contenido del correo
            $mail->isHTML(true);                                  // Establece el formato del correo a HTML
            $mail->Subject = $asunto;                            // Asunto del correo
            $mail->Body = mb_convert_encoding($cuerpo, 'UTF-8', 'ISO-8859-1'); // Cuerpo del correo, convierte la codificación

            // Establece el idioma del correo en español
            $mail->setLanguage('es', '../phpmailer/language/phpmailer.lang-es.php');

            // Intenta enviar el correo y devuelve true si es exitoso, de lo contrario, devuelve false
            if ($mail->send()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            // Captura y muestra errores si ocurren durante el envío del correo
            echo "Error al enviar el correo electrónico de la compra: {$mail->ErrorInfo}";
            return false;
        }
    }
}
