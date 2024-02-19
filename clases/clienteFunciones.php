<?php

// Establece una conexión a la base de datos MySQL
$con = new PDO("mysql:host=localhost;dbname=tienda_online", "root", "", [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"]);

// Función para verificar si algún elemento en un array es nulo o contiene solo espacios en blanco
function esNulo(array $parametros)
{
    foreach ($parametros as $parametro) {
        if (strlen(trim($parametro)) < 1) {
            return true;
        }
    }
    return false;
}

// Función para validar si una cadena es una dirección de correo electrónico válida
function esEmail($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    return false;
}

// Función para validar si dos contraseñas son iguales
function validaPassword($password, $repassword)
{
    if (strcmp($password, $repassword) === 0) {
        return true;
    }
    return false;
}

// Función para generar un token único
function generarToken()
{
    return md5(uniqid(mt_rand(), false));
}

// Función para registrar un cliente en la base de datos
function registraCliente(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO clientes (nombres, apellidos, email, telefono, dni, estatus, fecha_alta) VALUES (?,?,?,?,?,1, now())");
    if ($sql->execute($datos)) {
        return $con->lastInsertId();
    }
    return 0;
}

// Función para registrar un usuario en la base de datos
function registraUsuario(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO usuarios (usuario, password, token, id_cliente) VALUES (?,?,?,?)");
    if ($sql->execute($datos)) {
        return $con->lastInsertId();
    }
    return 0;
}

// Función para verificar si un usuario ya existe en la base de datos
function usuarioExiste($usuario, $con)
{
    $sql = $con->prepare("SELECT id FROM usuarios WHERE usuario LIKE ? LIMIT 1");
    $sql->execute([$usuario]);
    if ($sql->fetchColumn() > 0) {
        return true;
    }
    return false;
}

// Función para verificar si una dirección de correo electrónico ya existe en la base de datos
function emailExiste($email, $con)
{
    $sql = $con->prepare("SELECT id FROM clientes WHERE email LIKE ? LIMIT 1");
    $sql->execute([$email]);
    if ($sql->fetchColumn() > 0) {
        return true;
    }
    return false;
}

// Función para mostrar mensajes de error en una lista
function mostrarMensajes(array $errors)
{
    if (count($errors) > 0) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert"><ul>';
        foreach ($errors as $error) {
            echo  '<li>' . $error . '</li>';
        }
        echo '</ul></div>'; // Cierra la div de la alerta aquí
    }
}

// Función para validar un token y activar una cuenta de usuario
function validaToken($id, $token, $con)
{
    $msg = "";
    $sql = $con->prepare("SELECT id FROM usuarios WHERE id = ? AND token LIKE ? LIMIT 1");
    $sql->execute([$id, $token]);
    if ($sql->fetchColumn() > 0) {
        if (activarUsuario($id, $con)) {
            $msg = "Cuenta activada.";
        } else {
            $msg = "Error al activar cuenta.";
        }
    } else {
        $msg = "No existe el registro del cliente";
    }
    return $msg;
}


function activarUsuario($id, $con)
{

    // Comprobar si el usuario existe
    $sql = $con->prepare("SELECT COUNT(*) AS count FROM usuarios WHERE id = ?");
    $sql->execute([$id]);
    $count = $sql->fetchColumn();

    if ($count == 0) {
        // El usuario no existe
        echo "El usuario con ID $id no existe.";
        return false;
    }

    try {
        // Actuar el usuario
        $sql = $con->prepare("UPDATE usuarios SET activacion = 1 WHERE id = ?");
        $sql->execute([$id]);
        return true; // Indicar que la activación fue exitosa
    } catch (PDOException $e) {
        // Manejar el error aquí, por ejemplo, registrando el error en un archivo de registro
        echo "Error en la activación: " . $e->getMessage(); // Mostrar el mensaje de error
        return false; // Indicar que hubo un error en la activación
    }
}
function login($usuario, $password, $con, $proceso)
{
    $sql = $con->prepare("SELECT id, usuario, password, id_cliente FROM usuarios WHERE usuario LIKE ? LIMIT 1");
    $sql->execute([$usuario]);
    if ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
        if (esActivo($usuario, $con)) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['usuario'];
                $_SESSION['user_cliente'] = $row['id_cliente'];
                if ($proceso == 'pago') {
                    header("location: checkout.php");
                } else {
                    header("location: index.php");
                }
                exit;
            }
        } else {
            return 'El usuario no ha sido activado.';
        }
    }
    return 'El usuario y/o contaseña son incorrectos.';
}

function esActivo($usuario, $con)
{
    $sql = $con->prepare("SELECT activacion FROM usuarios WHERE usuario LIKE ? LIMIT 1");
    $sql->execute([$usuario]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    if ($row['activacion'] == 1) {
        return true;
    }
    return false;
}

function solicitaPassword($user_id, $con)
{
    $token = generarToken();
    $sql = $con->prepare("UPDATE usuarios SET token_password=?, password_request=1 WHERE id = ?");
    if ($sql->execute([$token, $user_id])) {
        return $token;
    }
    return null;
}

function verificaTokenRequest($user_id, $token, $con)
{
    $sql = $con->prepare("SELECT id FROM usuarios WHERE id = ? AND token_password LIKE ? AND password_request=1 LIMIT 1");
    $sql->execute([$user_id, $token]);
    if ($sql->fetchColumn() > 0) {
        return true;
    }
    return false;
}

function actualizaPassword($user_id, $password, $con)
{
    $sql = $con->prepare("UPDATE usuarios SET password=?, token_password = '', password_request = 0 WHERE id = ?");
    if ($sql->execute([$password, $user_id])) {
        return true;
    }
    return false;
}
