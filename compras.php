<?php

require 'config/config.php';

require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$token = generarToken();
$_SESSION['token'] = $token;
$idCliente = $_SESSION['user_cliente'];

$sql = $con->prepare("SELECT id_transaccion, fecha, status, total, medio_pago FROM compra WHERE id_cliente = ? ORDER BY DATE(fecha) DESC");
$sql->execute([$idCliente]);

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

  <script src="https://kit.fontawesome.com/751f7f0eaa.js" crossorigin="anonymous"></script>
  <title>Store</title>
</head>

<body>
  <!--Menu de navegación-->
  <?php include 'menu.php'; ?>
  <!--Contenido-->
  <main>
    <div class="container">
      <h4>Mis compras</h4>


      <hr>

      <?php while ($row = $sql->fetch(PDO::FETCH_ASSOC)) { ?>
        <div class="card mb-3 border-success">
          <div class="card-header">
            <?php echo $row['fecha']; ?>
          </div>
          <div class="card-body">
            <h5 class="card-title">Folio: <?php echo $row['id_transaccion']; ?></h5>
            <p class="card-text">Total: <?php echo $row['total']; ?></p>
            <a href="compra_detalle.php?orden=<?php echo $row['id_transaccion']; ?>&token=<?php echo $token; ?>" class="btn btn-primary">Ver compra</a>

          </div>
        </div>
      <?php } ?>

    </div>
  </main>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>

</body>

</html>