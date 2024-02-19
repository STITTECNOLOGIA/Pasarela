<?php

require 'config/config.php';

require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$errors = [];

if (!empty($_POST)) {
  $nombres = trim($_POST['nombres']);
  $apellidos = trim($_POST['apellidos']);
  $email = trim($_POST['email']);
  $telefono = trim($_POST['telefono']);
  $dni = trim($_POST['dni']);
  $usuario = trim($_POST['usuario']);
  $password = trim($_POST['password']);
  $repassword = trim($_POST['repassword']);

  if (esNulo([$nombres, $apellidos, $email, $telefono, $dni, $usuario, $password, $repassword])) {
    $errors[] = "debe llenar todos los campos";
  }
  if (!esEmail($email)) {
    $errors[] = "La direccion de correo no es valida";
  }

  if (!validaPassword($password, $repassword)) {
    $errors[] = "Las contraseñas no conciden";
  }

  if (usuarioExiste($usuario, $con)) {
    $errors[] = "El nombre de usuario $usuario ya existe";
  }

  if (emailExiste($email, $con)) {
    $errors[] = "El correo electronico $email ya existe";
  }

  if (count($errors) == 0) {

    $id = registraCliente([$nombres, $apellidos, $email, $telefono, $dni], $con);

    if ($id > 0) {

      require 'clases/Mailer.php';
      $mailer = new Mailer();
      $token = generarToken();
      $pass_hash = password_hash($password, PASSWORD_DEFAULT);

      $idUsuario = (registraUsuario([$usuario, $pass_hash, $token, $id], $con));

      if ($idUsuario > 0) {

        $url = SITE_URL . 'activa_cliente.php?id=' . $idUsuario . '&token=' . $token;
        //http://localhost/pasarela/activa_cliente.php?id=23&token=53bdd608790786f28b4ba56d38c955c8
        $asunto = "Activar cuenta - Tienda S.T.I.T.";
        $cuerpo = "Estimado $nombres: <br> Para continuar con el proceso de registro es necesario dar click en el siguiente Link <a href='$url'>Activar cuenta</a>";


        if ($mailer->enviarEmail($email, $asunto, $cuerpo)) {
          echo "Para terminar el proceso de registro de registro sige las instrucciones que le hemos enviado a la direccción de correo electronico $email";
          exit;
        }
      } else {
        $errors[] = "Error al registrar usuario";
      }
    } else {
      $errors[] = "Error al registrar cliente";
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
  <main>
    <div class="container">
      <h2>Datos del cliente </h2>

      <?php mostrarMensajes($errors);  ?>

      <form class="row g-3" action="registro.php" method="post" autocomplete="off">
        <div class="col-md-6">
          <label for="nombres"><span class="text-danger">*</span>Nombres</label>
          <input type="text" name="nombres" id="nombres" class="form-control" value="<?php $nombres ?>" requireda>
        </div>

        <div class="col-md-6">
          <label for="apellidos"><span class="text-danger">*</span>Apellidos</label>
          <input type="text" name="apellidos" id="apellidos" class="form-control" requireda>
        </div>

        <div class="col-md-6">
          <label for="email"><span class="text-danger">*</span>Correo electronico</label>
          <input type="email" name="email" id="email" class="form-control" requireda>
          <span id="validaEmail" class="text-danger"></span>
        </div>

        <div class="col-md-6">
          <label for="telefono"><span class="text-danger">*</span>Télefono</label>
          <input type="tel" name="telefono" id="telefono" class="form-control" requireda>
        </div>

        <div class="col-md-6">
          <label for="dni"><span class="text-danger">*</span>Documento</label>
          <input type="text" name="dni" id="dni" class="form-control" requireda>
        </div>

        <div class="col-md-6">
          <label for="usuario"><span class="text-danger">*</span>Usuario</label>
          <input type="text" name="usuario" id="usuario" class="form-control" requireda>
          <span id="validaUsuario" class="text-danger"></span>
        </div>

        <div class="col-md-6">
          <label for="password"><span class="text-danger">*</span>Contraseña</label>
          <input type="password" name="password" id="password" class="form-control" requireda>
        </div>

        <div class="col-md-6">
          <label for="repassword"><span class="text-danger">*</span>Repetir contraseña</label>
          <input type="password" name="repassword" id="repassword" class="form-control" requireda>
        </div>

        <i><b>Nota:</b> Los campos con asterisco son obligatorios</i>

        <div class="col-12">
          <button type="submit" class="btn btn-primary">Registrar</button>
        </div>

      </form>

    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <script>
    let txtUsuario = document.getElementById('usuario');
    txtUsuario.addEventListener("blur", function() {
      existeUsuario(txtUsuario.value);
    }, false);

    let txtemail = document.getElementById('email');
    txtemail.addEventListener("blur", function() {
      existeEmail(txtemail.value);
    }, false);

    function existeEmail(email) {
      let url = "clases/clienteAjax.php";
      let formData = new FormData();
      formData.append("action", "existeEmail");
      formData.append("email", email);

      fetch(url, {
          method: 'POST',
          body: formData
        }).then(response => response.json())
        .then(data => {
          if (data.ok) {
            document.getElementById('email').value = '';
            document.getElementById('validaEmail').innerHTML = 'Correo no disponible';
          } else {
            document.getElementById('validaEmail').innerHTML = '';
          }
        });
    }

    function existeUsuario(usuario) {
      let url = "clases/clienteAjax.php";
      let formData = new FormData();
      formData.append("action", "existeUsuario");
      formData.append("usuario", usuario);

      fetch(url, {
          method: 'POST',
          body: formData
        }).then(response => response.json())
        .then(data => {
          if (data.ok) {
            document.getElementById('usuario').value = '';
            document.getElementById('validaUsuario').innerHTML = 'Usuario no disponible';
          } else {
            document.getElementById('validaUsuario').innerHTML = '';
          }
        });
    }
  </script>


</body>

</html>