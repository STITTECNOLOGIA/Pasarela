<?php

require 'config/config.php';

require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$proceso = isset($_GET['pago']) ? 'pago' : 'login';

$errors = [];

if (!empty($_POST)) {

  $usuario = trim($_POST['usuario']);
  $password = trim($_POST['password']);
  $proceso = $_POST['proceso'] ?? 'login';
  if (esNulo([$usuario, $password])) {
    $errors[] = "debe llenar todos los campos";
  }
  if (count($errors) == 0) {
    $errors[] = login($usuario, $password, $con, $proceso);
  }
}



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
  <link rel="stylesheet" href="fontawesome/fontawesome-free-6.4.2-web/css/css.all">
  <title>Tienda en linea</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

  <link href="css/estilos.css" rel="stylesheet">

  <title>Store</title>
</head>

<body>
  <header data-bs-theme="dark">

    <div class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
      <div class="container">
        <a href="index.php" class="navbar-brand">

          <strong>Store</strong>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarHeader">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a href="#" class="nav-link active">Catalogo</a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link active">Contacto</a>
            </li>
          </ul>
          <a href="checkout.php" class="btn btn-primary">Carrito <span id="num_cart" class="badge bg-secondary" class="fa-solid fa-cart-shopping"><?php echo $num_cart; ?></span></a>
        </div>
      </div>
    </div>
  </header>
  <!--Contenido-->
  <main class="form-login m-auto pt-4" style="width: 350px">
    <h2>Iniciar sesión</h2>
    <?php mostrarMensajes($errors) ?>

    <form class="row g-3" action="login.php" method="post" autocomplete="off">

      <input type="hidden" name="proceso" value="<?php echo $proceso; ?>">
      <div class="form-floating">
        <input class="form-control" type="text" name="usuario" id="usuario" placeholder="Usuario" required>
        <label for="usuario">Usuario</label>
      </div>

      <div class="form-floating">
        <input class="form-control" type="password" name="password" id="password" placeholder="Contraseña" required>
        <label for="password">Contraseña</label>
      </div>

      <div class="col-12">
        <a href="recupera.php">¿Olvidaste tu Contraseña?</a>
      </div>

      <div class="d-grid gap-3 col-12">
        <button type="submit" class="btn btn-primary">Ingresar</button>
      </div>

      <Hr>
      <div class="col-12">
        ¿No tienes cuenta? <a href="registro.php">Registrate aquí</a>
      </div>
    </form>

  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>




</body>

</html>