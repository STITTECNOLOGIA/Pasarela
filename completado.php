<?php
require 'config/config.php';

$error = '';

try {
  $db = new Database();
  $con = $db->conectar();

  $id_transaccion = isset($_GET['key']) ? $_GET['key'] : '0';

  if ($id_transaccion == '') {
    $error = 'Error al procesar la petición';
  } else {
    $sql = $con->prepare("SELECT COUNT(id) FROM compra WHERE id_transaccion=? AND status=?");
    $sql->execute([$id_transaccion, 'COMPLETED']);
    $numRows = $sql->fetchColumn();

    if ($numRows > 0) {
      $sqlDet = $con->prepare("SELECT nombre, precio, cantidad FROM detalle_compra WHERE id_compra=?");
      $sqlDet->execute([$id_transaccion]);
    } else {
      $error = 'No se encontraron registros de compra para la transacción dada.';
    }
  }

  // Obtener la fecha y el total de la compra
  $sqlCompra = $con->prepare("SELECT fecha, total FROM compra WHERE id_transaccion=? AND status=?");
  $sqlCompra->execute([$id_transaccion, 'COMPLETED']);
  $compra = $sqlCompra->fetch(PDO::FETCH_ASSOC);

  if ($compra) {
    $fecha = $compra['fecha'];
    $total = $compra['total'];
  } else {
    $error = 'No se encontraron registros de compra para la transacción dada.';
  }
} catch (PDOException $e) {
  $error = 'Error en la base de datos: ' . $e->getMessage();
}

// Cierre de la conexión a la base de datos
$con = null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="css/estilos.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <title>Store</title>
</head>

<body>
  <!--Menu de navegación-->
  <?php include 'menu.php'; ?>
  <!--Contenido-->
  <main>
    <div class="container">
      <?php if (strlen($error) > 0) { ?>
        <div class="row">
          <div class="col">
            <h3><?php echo $error; ?></h3>
          </div>
        </div>
      <?php } else { ?>
        <div class="row">
          <div class="col">
            <b>Folio de la compra:</b><?php echo $id_transaccion; ?><br>
            <b>Fecha de compra:</b><?php echo $fecha; ?><br>
            <b>Total:</b><?php echo MONEDA . number_format($total, 2, '.', ','); ?><br>
          </div>
        </div>

        <?php if (isset($sqlDet)) { ?>
          <div class="row">
            <div class="col">
              <table class="table">
                <thead>
                  <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Importe</th>
                  </tr>
                </thead>

                <tbody>
                  <?php while ($row_det = $sqlDet->fetch(PDO::FETCH_ASSOC)) {
                    // Calcula el importe multiplicando precio y cantidad.
                    $importe = $row_det['precio'] * $row_det['cantidad'];
                  ?>
                    <tr>
                      <td><?php echo $row_det['nombre']; ?></td>
                      <td><?php echo $row_det['cantidad']; ?></td>
                      <td><?php echo $importe; ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        <?php } ?>
      <?php } ?>
    </div>
  </main>
</body>

</html>