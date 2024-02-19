<?php
// Se incluyen los archivos de configuración y base de datos
require '../config/config.php';


// Se establece la conexión a la base de datos
$db = new Database();
$con = $db->conectar();

// Se obtienen los datos en formato JSON de la solicitud POST
$json = file_get_contents('php://input');
$datos = json_decode($json, true);

// Se verifica si los datos decodificados son un array
if (is_array($datos)) {

    // Se obtiene el ID del cliente de la sesión
    $id_cliente = $_SESSION['user_cliente'];

    // Se realiza una consulta para obtener el correo electrónico del cliente
    $sql = $con->prepare("SELECT email FROM clientes WHERE id=? AND estatus=1");
    $sql->execute([$id_cliente]);
    $row_cliente = $sql->fetch(PDO::FETCH_ASSOC);

    // Se extraen detalles importantes de la transacción de PayPal
    $id_transaccion = $datos['detalles']['id'];
    $total = $datos['detalles']['purchase_units'][0]['amount']['value'];
    $status = $datos['detalles']['status'];
    $fecha = $datos['detalles']['update_time'];
    $fecha_nueva =  date('Y-m-d H:i:s', strtotime($fecha));
    // $email = $datos['detalles']['payer']['email_address'];
    $email = $row_cliente['email'];
    //$id_cliente = $datos['detalles']['payer']['payer_id'];

    // Se inserta la información de la transacción en la tabla de compras
    $sql = $con->prepare("INSERT INTO compra (id_transaccion, fecha, status, email, id_cliente, total, medio_pago) VALUES (?,?,?,?,?,?,?)");
    $sql->execute([$id_transaccion, $fecha_nueva, $status, $email, $id_cliente, $total, 'paypal']);
    $id = $con->lastInsertId();



    // Si la inserción es exitosa, se procesan los productos asociados con la transacción
    if ($id > 0) {
        $productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

        // Se obtienen y procesan los detalles de los productos
        if ($productos != null) {
            foreach ($productos as $clave => $cantidad) {
                $sql = $con->prepare("SELECT id, nombre, precio, descuento FROM productos WHERE id=? AND activo=1");
                $sql->execute([$clave]);
                $row_prod = $sql->fetch(PDO::FETCH_ASSOC);

                $precio = $row_prod['precio'];
                $descuento = $row_prod['descuento'];
                $precio_desc = $precio - (($precio * $descuento) / 100);

                // Se insertan los detalles de la compra en la tabla de detalle_compra
                $sql_insert =  $con->prepare("INSERT INTO detalle_compra (id_compra, id_producto, nombre, precio, cantidad) VALUES (?,?,?,?,?)");
                if ($sql_insert->execute([$id, $row_prod['id'], $row_prod['nombre'], $precio_desc, $cantidad])) {
                    restarStock($row_prod['id'], $cantidad, $con);
                }
            }

            // Se incluye el archivo Mailer.php y se envía un correo electrónico de confirmación al cliente
            require 'Mailer.php';

            $asunto = "Detalles de su compra";
            $cuerpo = '<h4>Gracias por su compra</h4>';  // Cuerpo del correo (mensaje)
            $cuerpo .= '<p>el ID de su compra es: <b>' . $id_transaccion . '</b></p>';

            $mailer = new Mailer;
            $mailer->enviarEmail($email, $asunto, $cuerpo);
        }

        // Se elimina la sesión del carrito
        unset($_SESSION['carrito']);
    }
}
function restarStock($id, $cantidad, $con)
{
    $sql = $con->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
    $sql->execute([$cantidad, $id]);
}
