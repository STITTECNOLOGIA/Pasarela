<?php

// Inicializa un array para almacenar los datos
$datos = [];

// Incluye el archivo de configuración de la base de datos
require_once '../config/database.php';

// Incluye el archivo de funciones relacionadas con el cliente
require_once 'clienteFunciones.php';

// Verifica si se ha enviado una acción a través de POST
if (isset($_POST['action'])) {
    // Obtiene la acción desde POST
    $action = $_POST['action'];

    // Crea una instancia de la clase Database para gestionar la conexión a la base de datos
    $db = new Database();
    $con = $db->conectar();

    // Verifica la acción recibida
    if ($action == 'existeUsuario') {
        // Verifica si el usuario existe en la base de datos y almacena el resultado en el array de datos
        $datos['ok'] = usuarioExiste($_POST['usuario'], $con);
    } elseif ($action == 'existeEmail') { // Cambio "=" a "==" para comparar igualdad
        // Verifica si el email existe en la base de datos y almacena el resultado en el array de datos
        $datos['ok'] = emailExiste($_POST['email'], $con);
    }
}

// Devuelve los datos en formato JSON
echo json_encode($datos);
