<?php
require_once 'config/config.php';

$db = new Database();
$con = $db->conectar();

$idCategoria = isset($_GET['cat']) ? $_GET['cat'] : '';
$orden = isset($_GET['orden']) ? $_GET['orden'] : '';

$orders = [
  'asc' => 'nombre ASC',
  'desc' => 'nombre DESC',
  'precio_alto' => 'precio DESC',
  'precio_bajo' => 'precio ASC',
];

$order = isset($orders[$orden]) ? $orders[$orden] : '';

if (!empty($order)) {
  $order = "ORDER BY $order";
}

if (!empty($idCategoria)) {
  $sql = $con->prepare("SELECT id, nombre, descripcion, precio FROM productos WHERE activo=1 AND id_categoria = ? $order");
  $sql->execute([$idCategoria]);
} else {
  $sql = $con->prepare("SELECT id, nombre, descripcion, precio FROM productos WHERE activo=1 $order");
  $sql->execute();
}

$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

$sqlCategorias = $con->prepare("SELECT id, nombre FROM cate WHERE activo=1");
$sqlCategorias->execute();
$categorias = $sqlCategorias->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="images/logo.png" type="image/x-icon" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
  <link href="css/estilos.css" rel="stylesheet">

  <script src="https://kit.fontawesome.com/751f7f0eaa.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  <title>Store</title>
</head>

<body class="d-flex flex-column h-100">
  <!--Menu de navegación-->
  <?php include 'menu.php'; ?>





  <!--Contenido-->
  <main class="flex-shrink-0">
    <div class="container">
      <div class="row">
        <div class="col-md-3 col-sm-6">
          <div class="card shadow-sm">
            <div class="card-header">
              Categorías
            </div>
            <div class="list-group">
              <a href="index.php" class="list-group-item list-group-item-action">
                Todo
              </a>
              <?php foreach ($categorias as $cate) { ?>
                <a href="index.php?cat=<?php echo $cate['id']; ?>" class="list-group-item list-group-item-action <?php if ($idCategoria == $cate['id']) echo 'active'; ?>">
                  <?php echo $cate['nombre']; ?>
                </a>
              <?php } ?>
            </div>
          </div>
        </div>


        <div class="col-12 col-md-9">
          <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 justify-content-end g-4">
            <div class="col mb-2">
              <form action="index.php" id="ordenForm" method="get">
                <input type="hidden" name="cat" id="cat" value="<?php echo $idCategoria; ?>">
                <select name="orden" id="orden" class="form-select form-select-sm" onchange="submitForm()">
                  <option value="">Ordenar por</option>
                  <option value="precio_alto" <?php echo ($orden === 'precio_alto') ? 'selected' : ''; ?>>Precios más altos</option>
                  <option value="precio_bajo" <?php echo ($orden === 'precio_bajo') ? 'selected' : ''; ?>>Precios más bajos</option>
                  <option value="asc" <?php echo ($orden === 'asc') ? 'selected' : ''; ?>>Nombre A-Z</option>
                  <option value="desc" <?php echo ($orden === 'desc') ? 'selected' : ''; ?>>Nombre Z-A</option>
                </select>
              </form>
            </div>
          </div>

          <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            <?php foreach ($resultado as $row) { ?>
              <div class="col">
                <div class="card shadow-sm h-100">
                  <?php
                  $id = $row['id'];
                  $imagen = "images/productos/" . $id . "/principal.jpg";

                  if (!file_exists($imagen)) {
                    $imagen = "images/imagen-no-disponible.png";
                  }
                  ?>
                  <img src="<?php echo $imagen; ?>" class="img-fluid">
                  <div class="card-body d-flex flex-column justify-content-between">
                    <h5 class="card-title"><?php echo $row['nombre']; ?></h5>
                    <p class="card-text"><?php echo number_format($row['precio'], 2, ".", "."); ?></p>
                    <div class="d-flex justify-content-between align-items-center">
                      <div class="btn-group">
                        <a href="details.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>" class="btn btn-primary">Detalles</a>
                      </div>
                      <button class="btn btn-outline-success" type="button" onClick="addProducto(<?php echo $row['id']; ?>, '<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>')">Comprar</button>
                    </div>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </main>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <script>
    function addProducto(id, token) {
      var url = 'clases/carrito.php';
      var formData = new FormData();
      formData.append('id', id);
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
            Swal.fire({
              icon: 'warning',
              title: 'No hay existencias de este producto actualmente',
              showConfirmButton: false,
              timer: 2000 // Puedes ajustar el tiempo que aparece el mensaje cambiando este valor
            });
          }
        })
    }

    function submitForm() {
      document.getElementById('ordenForm').submit();
    }
  </script>

</body>

</html>