<?php
// Nombre de la aplicación
define('APP_NAME', 'Sistema de anf115');

// Obtiene las credenciales de conexión desde las variables de entorno de Clever Cloud
$dbname = getenv('MYSQL_ADDON_DB');
$host = getenv('MYSQL_ADDON_HOST');
$port = getenv('MYSQL_ADDON_PORT');
$user = getenv('MYSQL_ADDON_USER');
$password = getenv('MYSQL_ADDON_PASSWORD');

// Construye el DSN para PDO
$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8";

try {
    // Conecta a la base de datos usando PDO
    $pdo = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    // echo "Conexion Exitosa"; // Puedes descomentar esta línea para probar la conexión
} catch (PDOException $e) {
    // En caso de error de conexión, imprime el mensaje de error
    echo "Error de conexión a la base de datos: " . $e->getMessage();
    exit(); // Detiene la ejecución si falla la conexión
}

//CREAMOS UNA VARIABLE PARA LA FECHA Y HORA, esta la usamos en controller_create.php
date_default_timezone_set('America/El_Salvador');
$fyh_creacion = date('Y-m-d H:i:s');
$fyh_actualizacion = date('Y-m-d H:i:s');

$URL = "https://www-sistemadeanalisisfinanciero-com.onrender.com";
$VIEWS = "https://www-sistemadeanalisisfinanciero-com.onrender.com/views";

