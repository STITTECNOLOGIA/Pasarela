<?php

require '../config/database.php';
require '../config/config.php';




$db = new Database();
$con = $db->conectar();

$id = $_POST['id'];
$nombre = $_POST['nombre'];

$sql = $con->prepare('UPDATE productos SET activo = 0 WHERE id = ?');
$sql->execute([$id]);

header('Location: index.php');
