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

foreach($datos as $dato){
    $config[$dato["nombre"]] = $dato["valor"];
}
?>



<main>
    <div class="container-fluid px-4">
        <h2 class="mt-4">Nueva categoría</h2>

        <form action="guarda.php" method="post" autocomplete="off">
            <div class="mb-3">
              <label for="nombre" class="form-label">Nombre</label>
              <input type="text"
                class="form-control" name="nombre" id="nombre" required autofocus>
              
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>

        </form>
    </div>
</main>



<?php require '../footer.php'; ?>