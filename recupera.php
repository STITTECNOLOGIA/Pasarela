<?php

require 'config/config.php';

require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$errors = [];

if (!empty($_POST)) {

  $email = trim($_POST['email']);

  if (esNulo([$email])) {
    $errors[] = "debe llenar todos los campos";
  }
  if (!esEmail($email)) {
    $errors[] = "La direccion de correo no es valida";
  }
  if (count($errors) == 0) {
    if (emailExiste($email, $con)) {
      $sql = $con->prepare("SELECT usuarios.id, clientes.nombres FROM usuarios INNER JOIN clientes ON usuarios.id_cliente=clientes.id WHERE clientes.email LIKE ? LIMIT 1");
      $sql->execute([$email]);
      $row = $sql->fetch(PDO::FETCH_ASSOC);
      $user_id = $row['id'];
      $nombre = $row['nombres'];

      $token = solicitaPassword($user_id, $con);

      if ($token !== null) {
        require 'clases/Mailer.php';
        $mailer = new Mailer();

        $url = SITE_URL . 'reset_password.php?id=' . $user_id . '&token=' . $token;

        $asunto = "Recuperar password - Tienda S.T.I.T.";
        $cuerpo = "Estimado $nombre: <br> Si has solicitado el cambio de tu contraseña da click en el siguiente Link <a href='$url'>$url</a>.";
        $cuerpo .= "<br>Si no hiciste esta solicitud puedes ignorar este correo.";

        if ($mailer->enviarEmail($email, $asunto, $cuerpo)) {
          echo "<p><b>Correo enviado</b></p> $email";
          echo "<p>Hemos enviado un correo electrónico a la dirección $email para restablecer la contraseña.</p>";
          exit;
        }
      }
    } else {
      $errors[] = "No existe una cuenta asociada a este correo electrónico";
    }
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
    <h3>Recuperar contraseña</h3>

    <?php mostrarMensajes($errors) ?>

    <form action="recupera.php" method="post" class="row g-3" autocomplete="off">

      <div class="form-floating">
        <input class="form-control" type="email" name="email" id="email" placeholder="Correo electronico" required>
        <label for="email">Correo electronico</label>
      </div>

      <div class="d-grid gap-3 col-12">
        <button type="submit" class="btn btn-primary">Continuar</button>
      </div>

      <div class="col-12">
        ¿No tienes cuenta? <a href="registro.php">Registrate aquí</a>
      </div>

    </form>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>




</body>

</html>