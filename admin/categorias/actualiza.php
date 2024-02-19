<?php

require '../config/database.php';
require '../config/config.php';




$db = new Database();
$con = $db->conectar();

$id = $_POST['id'];
$nombre = $_POST['nombre'];

$sql = $con->prepare('UPDATE cate SET nombre = ? WHERE id = ?');
$sql->execute([$nombre, $id]);

header('Location: index.php');


?>







