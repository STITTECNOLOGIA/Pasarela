<?php

$path = dirname(__FILE__) . DIRECTORY_SEPARATOR;


require_once $path . 'database.php';

$db = new Database();
$con = $db->conectar();

$sql = "SELECT nombre, valor FROM configuracion";
$resultado = $con->query($sql);
$datos = $resultado->fetchAll(PDO::FETCH_ASSOC);

$config = [];

foreach ($datos as $dato) {
    $config[$dato["nombre"]] = $dato["valor"];
}

// Datos para el envío de correo electrónico
define("MAIL_HOST", $config["correo_smtp"]);
define("MAIL_USER", $config["correo_electronico"]);
define("MAIL_PASS", $config["correo_password"]);
define("MAIL_PORT", $config["correo_puerto"]);



// API de PayPal
define("CLIENT_ID", "AeUAzRfE3B3X6bVS98Fnh7rYNURfcmhK1HEUWAlvywAFeun2tzFndrxmKEuf7CCgVA3vK6RVLZN7c5pz");

// API de Mercado Pago
define("TOKEN_MP", "TEST-5838720071590582-072121-7367aa7e4051ffeee39624ecf1a0db5a-1304968173");
define("CURRENCY", "US");

//Configuracion del sistema
define('SITE_URL', 'http://localhost/pasarela/');


define("KEY_TOKEN", "APR.wqc-0000**");
define("MONEDA", "$");

// Inicia una sesión de PHP (si no está iniciada)
session_start();

$num_cart = 0;
// Verifica si existe un carrito de compras en la sesión
if (isset($_SESSION['carrito']['productos'])) {
    // Obtiene el número de productos en el carrito
    $num_cart = count($_SESSION['carrito']['productos']);
}
