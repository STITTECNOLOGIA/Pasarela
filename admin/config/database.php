<?php

class Database
{
    // Definición de variables privadas para la información de la base de datos
    private $hostname = "localhost";
    private $database = "tienda_online";
    private $username = "root";
    private $password = "";
    private $charset = "utf8";

    // Método para establecer una conexión a la base de datos
    function conectar()
    {
        try {
            // Construye la cadena de conexión utilizando la información proporcionada
            $conexion = "mysql:host=" . $this->hostname . "; dbname=" . $this->database . "; charset" . $this->charset;

            // Configura opciones para la conexión PDO
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Habilita el manejo de errores excepcionales
                PDO::ATTR_EMULATE_PREPARES => false // Deshabilita la emulación de consultas preparadas
            ];

            // Crea una instancia de la clase PDO y establece la conexión a la base de datos
            $pdo = new PDO($conexion, $this->username, $this->password, $options);

            // Retorna la instancia de PDO, que representa la conexión a la base de datos
            return $pdo;
        } catch (PDOException $e) {
            // En caso de un error en la conexión, muestra un mensaje de error y sale del script
            echo 'Error de conexión: ' . $e->getMessage();
            exit;
        }
    }
}
