<?php

require '../config/database.php';
require '../config/config.php';
require '../header.php';


$db = new Database();
$con = $db->conectar();

$nombre = $_POST['nombre'];
$telefono = $_POST['telefono'];
$moneda = $_POST['moneda'];

$smtp = $_POST['smtp'];
$puerto = $_POST['puerto'];
$email = $_POST['email'];
$password = ($_POST['password']);

$paypal_cliente = $_POST['paypal_cliente'];
$paypal_moneda = $_POST['paypal_moneda'];

$mp_token = $_POST['mp_token'];
$mp_clave = $_POST['mp_clave'];

$passwordBd = '';
$sqlConfig->$con->query("SELECT valor FROM configuracion WHERE nombre = 'correo_password' ");
$sqlConfig->execute();
if ($row_config = $sqlConfig->fetch(PDO::FETCH_ASSOC)) {
    $passwordBd = $row_config["valor"];
}

$sql = $con->prepare('UPDATE configuracion SET valor = ? WHERE nombre = ?');
$sql->execute([$nombre, 'tienda_nombre']);
$sql->execute([$telefono, 'tienda_telefono']);
$sql->execute([$moneda, 'tienda_moneda']);
$sql->execute([$smtp, 'correo_smtp']);
$sql->execute([$puerto, 'correo_puerto']);
$sql->execute([$email, 'correo_electronico']);
$sql->execute([$password, 'correo_password']);
$sql->execute([$paypal_cliente, 'paypal_cliente']);
$sql->execute([$paypal_moneda, 'paypal_moneda']);
$sql->execute([$mp_token, 'mp_token']);
$sql->execute([$mp_clave, 'mp_clave']);





?>


<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Configuración actializada</h1>
        <a href="index.php" class="btn btn-secondary">Regresar</a>
    </div>
</main>