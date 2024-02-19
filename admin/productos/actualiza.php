<?php

// Se incluyen los archivos de configuración de la base de datos y otras configuraciones
require '../config/database.php';
require '../config/config.php';

// Función para redimensionar una imagen
function redimensionarImagen($archivoOrigen, $archivoDestino, $nuevoAncho, $nuevoAlto)
{
    list($ancho, $alto) = getimagesize($archivoOrigen);
    $nuevaImagen = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
    $imagen = imagecreatefromjpeg($archivoOrigen); // Cambia esta función según la extensión de archivo que estés manejando

    imagecopyresampled($nuevaImagen, $imagen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

    imagejpeg($nuevaImagen, $archivoDestino); // Cambia esta función según la extensión de archivo que estés manejando

    // Libera memoria
    imagedestroy($nuevaImagen);
    imagedestroy($imagen);
}

// Se crea una instancia de la clase Database para la conexión a la base de datos
$db = new Database();
$con = $db->conectar(); // Se establece la conexión y se guarda en la variable $con

// Se obtienen los datos del formulario enviado mediante el método POST
$id = $_POST['id'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$descuento = $_POST['descuento'];
$stock = $_POST['stock'];
$categoria = $_POST['categoria'];

$sql = "UPDATE productos SET nombre=?, descripcion=?, precio=?, descuento=?, stock=?, id_categoria = ? WHERE id = ?";
$stm = $con->prepare($sql);

if ($stm->execute([$nombre, $descripcion, $precio, $descuento, $stock, $categoria, $id])) {


    $dir = '../../images/productos/' . $id . '/';
    $permitidos = ['jpeg', 'jpg', 'png'];

    if ($_FILES["imagen_principal"]["error"] == UPLOAD_ERR_OK) {
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $extension = strtolower(pathinfo($_FILES['imagen_principal']['name'], PATHINFO_EXTENSION));

        if (in_array($extension, $permitidos)) {
            $ruta_img = $dir . 'principal.' . $extension;

            if (move_uploaded_file($_FILES['imagen_principal']['tmp_name'], $ruta_img)) {
                echo "El archivo se cargó correctamente.";
            } else {
                echo "Error al cargar el archivo.";
            }
        } else {
            echo "Archivo no permitido.";
        }
    } else {
        echo "No enviaste el archivo.";
    }

    if (isset($_FILES['otras_imagenes'])) {
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }


        foreach ($_FILES['otras_imagenes']['tmp_name'] as $key => $tmp_name) {
            $fileName = $_FILES['otras_imagenes']['name'][$key];
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $nuevoNombre = $dir . uniqid() . '.' . $extension;

            if (in_array($extension, $permitidos)) {

                if (move_uploaded_file($tmp_name, $nuevoNombre)) {
                    echo "El archivo se cargó correctamente.<br>";
                } else {
                    echo "Error al cargar el archivo.";
                }
            } else {
                echo "Archivo no permitido.";
            }
        }
    }
} else {
    echo "Error al insertar datos del producto.";
}




header('Location: index.php');
