<?php
require_once 'config/config.php';

$db = new Database();
$con = $db->conectar();

$query = $_POST['query'] ?? '';

if (!empty($query)) {
    $sql = $con->prepare("SELECT id, nombre, descripcion, precio FROM productos WHERE activo=1 AND (nombre LIKE ? OR descripcion LIKE ?)");
    $sql->execute(["%$query%", "%$query%"]);
    $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Si la consulta está vacía, muestra todos los productos
    $sql = $con->prepare("SELECT id, nombre, descripcion, precio FROM productos WHERE activo=1");
    $sql->execute();
    $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
}

foreach ($resultado as $row) {
    // Aquí imprime el HTML de cada resultado de la búsqueda
    echo '<div class="col">';
    echo '  <div class="card shadow-sm h-100">';
    // Resto del código para mostrar el producto...
    echo '  </div>';
    echo '</div>';
}
