<?php
require '../config/config.php';
require '../config/database.php';
require '../clases/adminFunciones.php';




$user_id = $_GET['user_id'] ?? $_POST['user_id'] ?? '';


if ($user_id == '') {
    header("Location: index.php");
    exit;
}

$db = new Database();
$con = $db->conectar();

$errors = [];



if (!empty($_POST)) {
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if (esNulo([$user_id,  $password, $repassword])) {
        $errors[] = "Debe llenar todos los campos";
    }
    if (!validaPassword($password, $repassword)) {
        $errors[] = "Las contraseñas no coinciden";
    }
    if (empty($errors)) {
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        if (actualizaPassword($user_id, $pass_hash, $con)) {
            $errors[] = "Contraseña modificada.";
        } else {
            $errors[] = "Error al modificar contraseña. Inténtalo nuevamente.";
        }
    }
}

$sql = "SELECT id, usuario FROM usuarios WHERE id = ?";
$sql = $con->prepare($sql);
$sql->execute([$user_id]);
$usuario = $sql->fetch(PDO::FETCH_ASSOC);

require '../header.php';
?>


<!--Contenido-->
<main class="form-login m-auto pt-4" style="width: 350px">
    <h3>Cambiar contraseña</h3>

    <?php mostrarMensajes($errors) ?>

    <form action="cambiar_password.php" method="post" class="row g-3" autocomplete="off">
        <input type="hidden" name="user_id" value="<?php echo $usuario['id']; ?>">

        <div class="form-floating">
            <input class="form-control" type="text" id="password" value="<?php echo $usuario['usuario']; ?>" disabled>
            <label for="usuario">Usuario</label>
        </div>


        <div class="form-floating">
            <input class="form-control" type="password" name="password" id="password" placeholder="Nueva contraseña" required>
            <label for="password">Nueva contraseña</label>
        </div>

        <div class="form-floating">
            <input class="form-control" type="password" name="repassword" id="repassword" placeholder="Confirmar contraseña" required>
            <label for="repassword">Confirmar contraseña</label>
        </div>




        <div class="d-grid gap-3 col-12">
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>



    </form>
</main>

<?php include '../footer.php';
