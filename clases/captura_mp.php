<?php

use MercadoPago\Payment;

require 'config/config.php';

$db = new Database();
$con = $db->conectar();

$id_transaccion = isset($_GET['status']) ? $_GET['payment_id'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';


if ($id_transaccion != '') {

    $fecha_nueva =  date('Y-m-d H:i:s');
    $monto = isset($_SESSION['carrito']['total']) ? $_SESSION['carrito']['total'] : 0;
    $id_cliente = $_SESSION['user_cliente'];
    $sql = $con->prepare("SELECT email FROM clientes WHERE id=? AND estatus=1");
    $sql->execute([$id_cliente]);
    $row_cliente = $sql->fetch(PDO::FETCH_ASSOC);
    $email = $row_cliente['email'];

    $comando = $con->prepare("INSERT INTO compra (fecha, status, email, id_cliente, total, id_transaccion, medio_pago) VALUES(?,?,?,?,?,?,?)");
    $comando->execute([$fecha_nueva, $status, $email, $id_cliente, $monto, $id_transaccion, 'MP']);
    $id = $con->lastInsertId();

    if ($id > 0) {
        $productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

        if ($productos != null) {
            foreach ($productos as $clave => $cantidad) {

                $sql = $con->prepare("SELECT id, nombre, precio, descuento FROM productos WHERE id=? AND activo=1");
                $sql->execute([$clave]);
                $row_prod = $sql->fetch(PDO::FETCH_ASSOC);

                $precio = $row_prod['precio'];
                $descuento = $row_prod['descuento'];
                $precio_desc = $precio - (($precio * $descuento) / 100);

                $sql_insert =  $con->prepare("INSERT INTO detalle_compra (id_compra, id_producto, nombre, precio, cantidad) VALUES (?,?,?,?,?)");
                $sql_insert->execute([$id, $clave, $row_prod['nombre'], $precio_desc, $cantidad]);
            }

            require 'Mailer.php';

            $asunto = "Detalles de su compra";
            $cuerpo = '<h4>Gracias por su compra</h4>';  // Cuerpo del correo (mensaje)
            $cuerpo .= '<p>el ID de su compra es: <b>' . $id_transaccion . '</b></p>';

            $mailer = new Mailer;
            $mailer->enviarEmail($email, $asunto, $cuerpo);
        }
        unset($_SESSION['carrito']);
        header("Location: " . SITE_URL . "completado.php?key=" . $id_transaccion);
    }
}
