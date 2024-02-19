<?php

require_once 'config/config.php';


$db = new Database();
$con = $db->conectar();

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($id == '' || $token == '') {
  echo 'Error al procesar la petición';
  exit;
} else {

  $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);
  if ($token == $token_tmp) {

    $sql = $con->prepare("SELECT count(id) FROM productos WHERE id=? AND activo=1");
    $sql->execute([$id]);
    if ($sql->fetchColumn() > 0) {

      $sql = $con->prepare("SELECT nombre, descripcion, precio, descuento FROM productos WHERE id=? AND activo=1 LIMIT 1");
      $sql->execute([$id]);
      $row = $sql->fetch(PDO::FETCH_ASSOC);
      $nombre = $row['nombre'];
      $descripcion = $row['descripcion'];
      $precio = $row['precio'];
      $descuento = $row['descuento'];
      $precio_desc = $precio - (($precio * $descuento) / 100);
      $dir_images = 'images/productos/' . $id . '/';

      $rutaimg = $dir_images . 'principal.jpg';

      if (!file_exists($rutaimg)) {
        $rutaimg = 'images/imagen-no-disponible.png';
      }

      $imagenes = array();
      if (file_exists($dir_images)) {
        $dir = dir($dir_images);

        while (($archivo = $dir->read()) != false) {
          $extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
          if ($archivo != 'imagen-no-disponible.png' && ($extension == 'png' || $extension == 'jpg')) {
            $imagenes[] = $dir_images . $archivo;
          }
        }

        $dir->close();
      }
    }
  } else {
    echo 'Error al procesar la petición';
    exit;
  }
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="shortcut icon" href="images/logo.png" type="image/x-icon" />
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
      <div class="row">
        <div class="col-md-6 order-md-1">

          <div id="carouselImages" class="carousel slide">
            <div class="carousel-indicators">
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img src="<?php echo $rutaimg; ?>" class="d-block w-100">
              </div>

              <<?php foreach ($imagenes as $img) { ?> <div class="carousel-item">
                <img src="<?php echo $img; ?>" class="d-block w-100">
            </div>
          <?php } ?>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselImages" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselImages" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>

        </div>






      </div>
      <div class="col-md-6 order-md-2">
        <h2><?php echo $nombre; ?></h2>

        <?php if ($descuento > 0) { ?>

          <p><del><?php echo MONEDA . number_format($precio, 2, '.', '.'); ?></del></p>
          <h2>
            <?php echo MONEDA . number_format($precio_desc, 2, '.', '.'); ?>
            <small class="text-success"><?php echo $descuento; ?>% descuento</small>
          </h2>

        <?php } else { ?>

          <h2><?php echo MONEDA . number_format($precio, 2, '.', '.'); ?></h2>

        <?php } ?>
        <p class="lead">
          <?php echo $descripcion ?>
        </p>

        <div class="col-3 my-3">
          Cantidad: <input class="form-control" id="cantidad" name="cantidad" type="number" min="1" max="10" value="1">
        </div>

        <div class="d-grid gap-3 col-10 mx-auto">
          <a href="pago.php" class="btn btn-primary">Comprar ahora</a>
          <button class="btn btn-outline-primary" type="button" onClick="addProducto(<?php echo $id; ?>, cantidad.value, '<?php echo $token_tmp; ?>')">Agregar al carrito</button>
        </div>

      </div>
    </div>
  </main>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <script>
    function addProducto(id, cantidad, token) {
      var url = 'clases/carrito.php';
      var formData = new FormData();
      formData.append('id', id);
      formData.append('cantidad', cantidad);
      formData.append('token', token);

      fetch(url, {
          method: 'POST',
          body: formData,
          mode: 'cors',
        }).then(response => response.json())
        .then(data => {
          if (data.ok) {
            let elemento = document.getElementById("num_cart")
            elemento.innerHTML = data.numero;
          } else {
            alert = "No hay existencias de este producto acyualmente";
          }
        })
    }
  </script>
</body>

</body>

</html>