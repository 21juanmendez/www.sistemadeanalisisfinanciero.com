<?php
// Incluir el archivo de configuración para la conexión a la base de datos
include('../../config.php');
session_start(); // Iniciar sesión para manejar mensajes de éxito o error

// Validar y sanitizar los parámetros id_cuenta y id_empresa
$id_cuenta = filter_input(INPUT_GET, 'id_cuenta', FILTER_VALIDATE_INT);
$id_empresa = filter_input(INPUT_GET, 'id_empresa', FILTER_VALIDATE_INT);

if (!$id_cuenta || !$id_empresa) {
    $_SESSION['icono'] = "error";
    $_SESSION['titulo'] = "Error";
    $_SESSION['mensaje'] = "Parámetros inválidos.";
    header("Location: ".$VIEWS."/catalogo/index.php?id_empresa=$id_empresa");
    exit();
}

try {
    // Preparar la consulta para eliminar la cuenta
    $sql = "DELETE FROM cuentas WHERE id_cuenta = :id_cuenta";
    $query = $pdo->prepare($sql);
    $query->bindParam(':id_cuenta', $id_cuenta, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($query->execute()) {
        $_SESSION['icono'] = "success";
        $_SESSION['titulo'] = "Éxito";
        $_SESSION['mensaje'] = "Cuenta eliminada correctamente.";
    } else {
        $_SESSION['icono'] = "error";
        $_SESSION['titulo'] = "Error";
        $_SESSION['mensaje'] = "Error al eliminar la cuenta. Por favor, intenta de nuevo.";
    }
} catch (PDOException $e) {
    // Capturar errores de SQL, especialmente los relacionados con restricciones de clave foránea
    $_SESSION['icono'] = "error";
    $_SESSION['titulo'] = "Error";
    $_SESSION['mensaje'] = "No se puede eliminar la cuenta debido a dependencias en otras tablas.";
}

// Redirigir a la página principal de cuentas de la empresa con el mensaje en la sesión
header("Location: ".$VIEWS."/catalogo/index.php?id_empresa=$id_empresa");
exit();
