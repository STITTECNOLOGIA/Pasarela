<?php

require '../config/database.php';
require '../config/config.php';
require '../fpdf/plantilla_reporte_compras.php';


$db = new Database();
$con = $db->conectar();

$fechaIni = $_POST['fecha_ini'];
$fechaFin = $_POST['fecha_fin'];

$query = "SELECT date_format(c.fecha, '%d/%m/%Y %H:%i' ) AS fechaHora, c.status, c.total, c.medio_pago, CONCAT
(cli.nombres,' ',cli.apellidos) AS cliente 
FROM compra AS c
INNER JOIN clientes AS cli ON c.id_cliente = cli.id
WHERE DATE(c.fecha) BETWEEN ? AND ?
ORDER BY DATE(fecha) ASC";
$resultado = $con->prepare($query);
$resultado->execute([$fechaIni, $fechaFin]);

$datos = [
    'fechaIni' => $fechaIni,
    'fechaFin' => $fechaFin
];

$pdf = new PDF('p', 'mm', 'Letter', $datos);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {

    $pdf->Cell(30, 6, $row['fechaHora'], 1, 0);
    $pdf->Cell(30, 6, $row['status'], 1, 0);
    $pdf->Cell(60, 6, mb_convert_encoding($row['cliente'], 'ISO-8859-1', 'UTF-8'), 1, 0);
    $pdf->Cell(30, 6, $row['total'], 1, 0);
    $pdf->Cell(30, 6, $row['medio_pago'], 1, 1);
}
$pdf->Output();
