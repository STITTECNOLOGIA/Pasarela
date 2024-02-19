<?php

// Incluye los archivos de configuración y las clases necesarias
require 'config/config.php';

require 'clases/clienteFunciones.php';

// Obtiene el valor del parámetro 'id' desde la URL (si está presente) o establece una cadena vacía si no está presente
$id = isset($_GET['id']) ? $_GET['id'] : '';
// Obtiene el valor del parámetro 'token' desde la URL (si está presente) o establece una cadena vacía si no está presente
$token = isset($_GET['token']) ? $_GET['token'] : '';

// Verifica si tanto 'id' como 'token' están en blanco
if ($id == '' || $token == '') {
    // Redirige al usuario a la página de inicio si falta alguno de los parámetros
    header('Location: index.php');
    exit; // Termina la ejecución del script
}

// Llama a la función validaToken para verificar el token y el ID en la base de datos y muestra el resultado
try {
    $resultado = validaToken($id, $token, $con);
} catch (Exception $e) {
    // Muestra un error si ocurre un error
    echo $e->getMessage();
}

// Muestra el resultado
echo $resultado;
