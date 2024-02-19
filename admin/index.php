<?php

require 'config/config.php';
require 'config/database.php';
require 'clases/adminFunciones.php';

$db = new Database();
$con = $db->conectar();

$errors = [];

if (!empty($_POST)) {
  $usuario = trim($_POST['usuario']);
  $password = trim($_POST['password']);

  if (esNulo([$usuario, $password])) {
    $errors[] = 'Debe llenar los campos';
  }

  if (empty($errors)) {
    $errors = login($usuario, $password, $con);
  }
}
?>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
<link href="css/estilos1.css" rel="stylesheet">

<script src="https://kit.fontawesome.com/751f7f0eaa.js" crossorigin="anonymous"></script>
<section class="vh-100 gradient-custom">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card bg-dark text-white" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">

            <div class="mb-md-5 mt-md-4 pb-5">

              <h2 class="fw-bold mb-2 text-uppercase">Login</h2>

              <form action="index.php" method="post" autocomplete="off">
                <div class="form-outline form-white mb-4">
                  <input type="text" id="usuario" name="usuario" class="form-control form-control-lg" placeholder="usuario" autofocus />
                  <label class="form-label" for="usuario">Usuario</label>
                </div>

                <div class="form-outline form-white mb-4">
                  <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="password" />
                  <label class="form-label" for="password">Password</label>
                </div>

                <?php mostrarMensajes($errors); ?>

                <p class="small mb-5 pb-lg-2"><a class="text-white-50" href="#!">Forgot password?</a></p>

                <button class="btn btn-outline-light btn-lg px-5" type="submit">Login</button>
              </form>


            </div>



          </div>
        </div>
      </div>
    </div>
  </div>
</section>