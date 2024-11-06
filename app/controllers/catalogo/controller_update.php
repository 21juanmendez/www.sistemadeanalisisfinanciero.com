<?php
// Incluir el archivo de configuración para la conexión a la base de datos
include('../../config.php');
session_start(); // Iniciar sesión para manejar mensajes de éxito o error

// Validar y sanitizar los datos del formulario
$id_empresa = filter_input(INPUT_POST, 'id_empresa', FILTER_VALIDATE_INT);
$id_cuenta = filter_input(INPUT_POST, 'id_cuenta', FILTER_VALIDATE_INT);
$nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
$id_tipo = filter_input(INPUT_POST, 'id_tipo', FILTER_VALIDATE_INT);
$id_clasificacion = filter_input(INPUT_POST, 'id_clasificacion', FILTER_VALIDATE_INT);
$saldo = filter_input(INPUT_POST, 'saldo', FILTER_VALIDATE_FLOAT);

// Validar que todos los campos requeridos estén presentes
if (!$id_cuenta || !$nombre || !$id_tipo || !$id_clasificacion || $saldo === false) {
    $_SESSION['icono'] = "error";
    $_SESSION['titulo'] = "Error";
    $_SESSION['mensaje'] = "Todos los campos son obligatorios y deben estar correctamente completados.";
    header("Location:" . $VIEWS . "/catalogo/update.php?id_empresa=$id_empresa&id_cuenta=$id_cuenta");
    exit();
}

// Consultar los datos actuales de la cuenta para verificar si hubo cambios
$sql_check = "SELECT nombre, id_tipo, id_clasificacion, saldo FROM cuentas WHERE id_cuenta = :id_cuenta";
$query_check = $pdo->prepare($sql_check);
$query_check->bindParam(':id_cuenta', $id_cuenta, PDO::PARAM_INT);
$query_check->execute();
$current_data = $query_check->fetch(PDO::FETCH_ASSOC);

// Verificar si no hubo cambios en los datos
if (
    $current_data &&
    $current_data['nombre'] === $nombre &&
    $current_data['id_tipo'] == $id_tipo &&
    $current_data['id_clasificacion'] == $id_clasificacion &&
    $current_data['saldo'] == $saldo
) {

    // Si no hubo cambios, mostrar mensaje sin actualización
    $_SESSION['icono'] = 'info';
    $_SESSION['titulo'] = 'Opps...';
    $_SESSION['mensaje'] = 'No se realizaron cambios en la cuenta';
    header("Location:" . $VIEWS . "/catalogo/index.php?id_empresa=$id_empresa");
    exit();
}

// Preparar la consulta SQL para actualizar los datos
$sql = "UPDATE cuentas SET 
            nombre = :nombre, 
            id_tipo = :id_tipo, 
            id_clasificacion = :id_clasificacion, 
            saldo = :saldo, 
            fyh_actualizacion = NOW() 
        WHERE id_cuenta = :id_cuenta";

$query = $pdo->prepare($sql);

// Enlazar los parámetros
$query->bindParam(':id_cuenta', $id_cuenta, PDO::PARAM_INT);
$query->bindParam(':nombre', $nombre, PDO::PARAM_STR);
$query->bindParam(':id_tipo', $id_tipo, PDO::PARAM_INT);
$query->bindParam(':id_clasificacion', $id_clasificacion, PDO::PARAM_INT);
$query->bindParam(':saldo', $saldo, PDO::PARAM_STR); // Utilizar PDO::PARAM_STR para decimales

// Ejecutar la consulta y verificar el resultado
if ($query->execute()) {
    $_SESSION['icono'] = "success";
    $_SESSION['titulo'] = "Éxito";
    $_SESSION['mensaje'] = "Cuenta actualizada correctamente.";
    header("Location:" . $VIEWS . "/catalogo/index.php?id_empresa=$id_empresa");
} else {
    $_SESSION['icono'] = "error";
    $_SESSION['titulo'] = "Error";
    $_SESSION['mensaje'] = "Error al actualizar la cuenta. Por favor, intenta de nuevo.";
    header("Location:" . $VIEWS . "/catalogo/update.php?id_empresa=$id_empresa&id_cuenta=$id_cuenta");
}
exit();
