<?php

require '../config/database.php';
require '../config/config.php';




$db = new Database();
$con = $db->conectar();

$nombre = $_POST['nombre'];

$sql = $con->prepare('INSERT INTO cate (nombre, activo) VALUES (?,1)');
$sql->execute([$nombre]);

header('Location: index.php');


?>







