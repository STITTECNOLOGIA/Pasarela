<?php
require 'config/config.php';
require 'config/database.php';
require 'clases/clienteFunciones.php';

$user_id = $_GET['id'] ?? $_POST['user_id'] ?? '';
$token = $_GET['token'] ?? $_POST['token'] ?? '';

if ($user_id == '' || $token == '') {
    header("Location: index.php");
    exit;
}

$db = new Database();
$con = $db->conectar();

$errors = [];

if (!verificaTokenRequest($user_id, $token, $con)) {
    echo "No se pudo verificar la información";
    exit;
}

if (!empty($_POST)) {
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if (esNulo([$user_id, $token, $password, $repassword])) {
        $errors[] = "Debe llenar todos los campos";
    }
    if (!validaPassword($password, $repassword)) {
        $errors[] = "Las contraseñas no coinciden";
    }
    if (count($errors) == 0) {
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        if (actualizaPassword($user_id, $pass_hash, $con)) {
            echo "Contraseña modificada.<br><a href='login.php'>Iniciar Sesión</a>";
            exit;
        } else {
            $errors[] = "Error al modificar contraseña. Inténtalo nuevamente.";
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
        <h3>Cambiar contraseña</h3>

        <?php mostrarMensajes($errors) ?>

        <form action="reset_password.php" method="post" class="row g-3" autocomplete="off">
            <input type="hidden" name="user_id" id="user_id" value="<?= $user_id; ?>" />
            <input type="hidden" name="token" id="token" value="<?= $token; ?>" />

            <div class="form-floating">
                <input class="form-control" type="password" name="password" id="password" placeholder="Nueva contraseña" required>
                <label for="password">Nueva contraseña</label>
            </div>

            <div class="form-floating">
                <input class="form-control" type="password" name="repassword" id="repassword" placeholder="Confirmar contraseña" required>
                <label for="repassword">Confirmar contraseña</label>
            </div>


            <div class="d-grid gap-3 col-12">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>

            <div class="col-12">
                <a href="login.php">Iniciar sesión</a>
            </div>

        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>




</body>

</html>