<?php

require '../config/config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['urlImagen'])) {
    $urlImagen = $_POST['urlImagen'];

    if (file_exists($urlImagen)) {
        unlink($urlImagen); // Eliminar la imagen del servidor

        // Aquí puedes realizar cualquier otro procesamiento necesario después de eliminar la imagen

        http_response_code(200); // Código 200 para indicar que la operación fue exitosa
        exit;
    } else {
        http_response_code(404); // La imagen no existe, código de error 404
        exit;
    }
} else {
    http_response_code(400); // Error en la solicitud, código de error 400
    exit;
}
