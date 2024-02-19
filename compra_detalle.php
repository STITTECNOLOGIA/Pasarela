<?php

require 'config/config.php';

require 'clases/clienteFunciones.php';

$token_session = $_SESSION['token'];
$orden = $_GET['orden'] ?? null;
$token = $_GET['token'] ?? null;

if ($orden == null || $token == null || $token != $token_session) {
  header('Location: compras.php');
  exit;
}

$db = new Database();
$con = $db->conectar();

$sqlCompra = $con->prepare('SELECT id, id_transaccion, fecha, total FROM compra WHERE id_transaccion = ? LIMIT 1');
$sqlCompra->execute([$orden]);
$rowCompra = $sqlCompra->fetch(PDO::FETCH_ASSOC);
$idCompra = $rowCompra['id'];

$fecha = new DateTime($rowCompra['fecha']);
$fecha = $fecha->format('Y/m/d H:i:s');

$sqlDetalle = $con->prepare('SELECT id, nombre, precio, cantidad FROM detalle_compra WHERE id_compra = ?');
$sqlDetalle->execute([$idCompra]);


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="shortcut icon" href="images/logo.png" type="image/x-icon" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
  <link href="css/estilos.css" rel="stylesheet">
  <link href="css/all.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/751f7f0eaa.js" crossorigin="anonymous"></script>
  <title>Store</title>
</head>

<body>
  <!--Menu de navegación-->
  <?php include 'menu.php'; ?>
  <!--Contenido-->
  <main>
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-4">
          <div class="card mb-3">
            <div class="card-header">
              <strong>Detalle de la compra</strong>
            </div>
            <div class="card-body">
              <p><strong>Fecha: </strong> <?php echo $fecha; ?></p>
              <p><strong>Orden: </strong> <?php echo $rowCompra['id_transaccion']; ?></p>
              <p><strong>total: </strong> <?php echo MONEDA . ' ' . number_format($rowCompra['total'], 2, '.', '.');  ?></p>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-8">
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Producto</th>
                  <th>Precio</th>
                  <th>Cantidad</th>
                  <th>Subtotal</th>
                </tr>
              </thead>

              <tbody>
                <?php while ($row = $sqlDetalle->fetch(PDO::FETCH_ASSOC)) {
                  $precio = $row['precio'];
                  $cantidad = $row['cantidad'];
                  $subtotal = $precio * $cantidad;
                ?>
                  <tr>
                    <td><?php echo $row['nombre']; ?></td>
                    <td><?php echo MONEDA . ' ' . number_format($precio, 2, '.', '.'); ?></td>
                    <td><?php echo $cantidad; ?></td>
                    <td><?php echo MONEDA . ' ' . number_format($subtotal, 2, '.', '.'); ?></td>
                  </tr>
                <?php } ?>
              </tbody>

            </table>
          </div>
        </div>

      </div>

    </div>
  </main>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>

</body>

</html>