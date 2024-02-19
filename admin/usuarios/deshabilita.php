<?php


require '../config/database.php';
require '../config/config.php';



$db = new Database();
$con = $db->conectar();

$id = $_POST['id'];

$sql = $con->prepare("UPDATE usuarios SET activacion = 0 WHERE id = ?");
$sql->execute([$id]);

header("Location: index.php");
