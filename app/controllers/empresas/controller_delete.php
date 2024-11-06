<?php

include('../../config.php');
session_start(); // Iniciar la sesión para utilizar variables de sesión

$id_empresa = filter_input(INPUT_GET, 'id_empresa', FILTER_VALIDATE_INT);

if ($id_empresa === false) {
    $_SESSION['icono'] = 'error';
    $_SESSION['titulo'] = 'Error';
    $_SESSION['mensaje'] = 'Parámetro inválido';
    header('Location: ' . $VIEWS . '/empresas/index.php');
    exit();
}

try {
    // Consulta segura usando bindParam
    $sql = "DELETE FROM empresa WHERE id_empresa = :id_empresa";
    $query = $pdo->prepare($sql);
    $query->bindParam(':id_empresa', $id_empresa, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($query->execute()) {
        $_SESSION['icono'] = 'success';
        $_SESSION['titulo'] = 'Correcto';
        $_SESSION['mensaje'] = 'Empresa eliminada correctamente';
    }
} catch (PDOException $e) {
    // Capturar el error si ocurre una violación de restricción de clave foránea
    if ($e->getCode() == 23000) {
        $_SESSION['icono'] = 'error';
        $_SESSION['titulo'] = 'Error';
        $_SESSION['mensaje'] = 'No se puede eliminar la empresa porque tiene cuentas asociadas.';
    } else {
        $_SESSION['icono'] = 'error';
        $_SESSION['titulo'] = 'Error';
        $_SESSION['mensaje'] = 'Error al eliminar la empresa';
    }
}

// Redirigir al index con los mensajes en la sesión
header('Location: ' . $VIEWS . '/empresas/index.php');
exit();
