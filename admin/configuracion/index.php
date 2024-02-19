<?php

require '../config/database.php';
require '../config/config.php';
require '../header.php';



$db = new Database();
$con = $db->conectar();

$sql = "SELECT nombre, valor FROM configuracion";
$resultado = $con->query($sql);
$datos = $resultado->fetchAll(PDO::FETCH_ASSOC);

$config = [];

foreach ($datos as $dato) {
  $config[$dato["nombre"]] = $dato["valor"];
}
?>

<main>
  <nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
      <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">SMTP Info</button>
      <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Profile</button>
      <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Contact</button>
      <!-- Nueva pestaña para la información de correo SMTP -->
      <button class="nav-link" id="nav-smtp-tab" data-bs-toggle="tab" data-bs-target="#nav-smtp" type="button" role="tab" aria-controls="nav-smtp" aria-selected="false">info</button>
    </div>
  </nav>
  <div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab"><!-- Aquí va la información de correo SMTP -->
      <div class="container-fluid px-4">
        <h2 class="mt-4">Configuración</h2>
        <form action="guarda.php" method="post">
          <div class="row">
            <div class="col-6">
              <label for="smtp">SMTP</label>
              <input class="form-control" type="text" name="smtp" id="smtp" value="<?php echo $config['correo_smtp']; ?>">
            </div>

            <div class="col-6">
              <label for="puerto">Puerto</label>
              <input class="form-control" type="text" name="puerto" id="puerto" value="<?php echo $config['correo_puerto']; ?>">
            </div>

            <div class="col-6">
              <label for="email">Correo electronico</label>
              <input class="form-control" type="email" name="email" id="email" value="<?php echo $config['correo_electronico']; ?>">
            </div>

            <div class="col-6">
              <label for="password">Contraseña</label>
              <input class="form-control" type="password" name="password" id="password" value="<?php echo $config['correo_password']; ?>">
            </div>

            <div class="row mt-4">
              <div class="col-12">
                <button class="btn btn-primary" type="submit">Guardar</button>
              </div>
            </div>

          </div>
      </div>
      <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">...</div>
      <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">...</div>
      <!-- Contenido para la nueva pestaña de SMTP -->
      <div class="tab-pane fade" id="nav-smtp" role="tabpanel" aria-labelledby="nav-smtp-tab">

        </form>
      </div>
</main>

<?php require '../footer.php'; ?>