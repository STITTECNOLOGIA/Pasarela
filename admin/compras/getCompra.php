<?php

require '../config/database.php';
require '../config/config.php';

$orden = $_POST['orden'] ?? null;

if ($orden == null) {
    exit;
}

$db = new Database();
$con = $db->conectar();

$sqlCompra = $con->prepare("SELECT compra.id, id_transaccion, fecha, total, CONCAT(nombres,' ',apellidos) AS cliente FROM compra INNER JOIN clientes ON compra.id_cliente = clientes.id WHERE id_transaccion = ? LIMIT 1");
$sqlCompra->execute([$orden]);
$rowCompra = $sqlCompra->fetch(PDO::FETCH_ASSOC);

if (!$rowCompra) {
    exit;
}

$idCompra = $rowCompra['id'];

$fecha = new DateTime($rowCompra['fecha']);
$fecha = $fecha->format('Y/m/d H:i:s');

$sqlDetalle = $con->prepare('SELECT id, nombre, precio, cantidad FROM detalle_compra WHERE id_compra = ?');
$sqlDetalle->execute([$idCompra]);

$detalles = array();
while ($row = $sqlDetalle->fetch(PDO::FETCH_ASSOC)) {
    $detalles[] = $row;
}

$response = array(
    'fecha' => $fecha,
    'orden' => $rowCompra['id_transaccion'],
    'total' => number_format($rowCompra['total'], 2, '.', '.'),
    'detalles' => $detalles
);

header('Content-Type: application/json');
echo json_encode($response);
