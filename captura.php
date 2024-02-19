<?php

use MercadoPago\Payment;

require 'config/config.php';

// Inicializar sesión si se están utilizando sesiones
session_start();

$db = new Database();
$con = $db->conectar();

$id_transaccion = isset($_GET['payment_id']) ? $_GET['payment_id'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

if ($id_transaccion != '') {
    try {
        $fecha_nueva = date('Y-m-d H:i:s');
        $monto = isset($_SESSION['carrito']['total']) ? $_SESSION['carrito']['total'] : 0;

        if (isset($_SESSION['user_cliente'])) {
            $id_cliente = $_SESSION['user_cliente'];

            // Obtener email del cliente
            $sql = $con->prepare("SELECT email FROM clientes WHERE id=? AND estatus=1");
            $sql->execute([$id_cliente]);
            $row_cliente = $sql->fetch(PDO::FETCH_ASSOC);
            $email = $row_cliente['email'];

            // Insertar compra
            $comando = $con->prepare("INSERT INTO compra (fecha, status, email, id_cliente, total, id_transaccion, medio_pago) VALUES(?,?,?,?,?,?,?)");
            $comando->execute([$fecha_nueva, $status, $email, $id_cliente, $monto, $id_transaccion, 'MP']);
            $id = $con->lastInsertId();

            if ($id > 0) {
                $productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

                if ($productos != null) {
                    foreach ($productos as $clave => $cantidad) {
                        // Obtener detalles del producto
                        $sql = $con->prepare("SELECT id, nombre, precio, descuento FROM productos WHERE id=? AND activo=1");
                        $sql->execute([$clave]);
                        $row_prod = $sql->fetch(PDO::FETCH_ASSOC);

                        $precio = $row_prod['precio'];
                        $descuento = $row_prod['descuento'];
                        $precio_desc = $precio - (($precio * $descuento) / 100);

                        // Insertar detalle de compra
                        $sql_insert = $con->prepare("INSERT INTO detalle_compra (id_compra, id_producto, nombre, precio, cantidad) VALUES (?,?,?,?,?)");
                        $sql_insert->execute([$id, $clave, $row_prod['nombre'], $precio_desc, $cantidad]);
                    }

                    // Enviar correo electrónico
                    require_once 'clases/Mailer.php';

                    $asunto = "Detalles de su compra";
                    $cuerpo = '<h4>Gracias por su compra</h4>';
                    $cuerpo .= '<p>El ID de su compra es: <b>' . $id_transaccion . '</b></p>';

                    $mailer = new Mailer();
                    $mailer->enviarEmail($email, $asunto, $cuerpo);

                    unset($_SESSION['carrito']);
                    header("Location: " . SITE_URL . "completado.php?key=" . $id_transaccion);
                    exit(); // Salir del script después de redireccionar
                } else {
                    throw new Exception("No hay productos en el carrito");
                }
            } else {
                throw new Exception("Error al insertar la compra");
            }
        } else {
            throw new Exception("No hay un usuario autenticado");
        }
    } catch (Exception $e) {
        // Registrar el error en un archivo de registro
        registrarError("Error en el script: " . $e->getMessage());
        echo "Error: " . $e->getMessage();
    }
}

// Función para registrar errores en un archivo de registro
function registrarError($mensaje)
{
    $archivoLog = 'errores.log';
    $lineaRegistro = date('Y-m-d H:i:s') . " - " . $mensaje . "\n";
    file_put_contents($archivoLog, $lineaRegistro, FILE_APPEND);
}
